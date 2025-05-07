<?php
require_once('includes/load.php');
page_require_level(3);

// Kiểm tra xem form đã được submit chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'] ?? '';
    $order_date_raw = $_POST['order_date'] ?? ''; // Lấy giá trị ngày thô từ form
    $product_ids = $_POST['product_id'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $total_price_from_form = $_POST['total_price'] ?? 0; // Giá trị total_price nhận từ form (chỉ để tham khảo)

    if (empty($customer_name) || empty($order_date_raw) || empty($product_ids)) {
        die("Lỗi: Vui lòng nhập đầy đủ thông tin đơn hàng.");
    }

    // Sử dụng kết nối từ class MySqli_DB
    $conn = $db->get_connection();

    if (!$conn) {
        die("Lỗi: Không thể kết nối database.");
    }

    // Bắt đầu transaction để đảm bảo tính nhất quán dữ liệu
    $conn->begin_transaction();
    $order_id = null;
    $success = true;

    try {
        // Xử lý và định dạng ngày đặt hàng
        $order_date = null;
        if (!empty($order_date_raw)) {
            $timestamp = strtotime($order_date_raw);
            error_log("save_order.php - timestamp: " . $timestamp); // Log timestamp
            if ($timestamp !== false) {
                $order_date = date('Y-m-d H:i:s', $timestamp); // Định dạng chuẩn YYYY-MM-DD HH:MM:SS
                error_log("save_order.php - order_date (sau date()): " . $order_date); // Log ngày đã định dạng
            } else {
                error_log("save_order.php - Lỗi: Định dạng ngày đặt hàng không hợp lệ. Giá trị raw: " . $order_date_raw);
                throw new Exception("Lỗi: Định dạng ngày đặt hàng không hợp lệ.");
            }
        } else {
            $order_date = date('Y-m-d H:i:s'); // Nếu không có ngày, dùng ngày giờ hiện tại (tùy chọn)
            error_log("save_order.php - order_date (mặc định): " . $order_date); // Log ngày mặc định
        }
        error_log("save_order.php - order_date sẽ được lưu: " . $order_date); // Log ngày sẽ lưu

        // Thêm đơn hàng vào bảng orders
        $stmt_order = $conn->prepare("INSERT INTO orders (customer_name, order_date) VALUES (?, ?)");
        $stmt_order->bind_param("ss", $customer_name, $order_date);
        if (!$stmt_order->execute()) {
            error_log("save_order.php - Lỗi khi thêm đơn hàng: " . $stmt_order->error);
            throw new Exception("Lỗi khi thêm đơn hàng: " . $stmt_order->error);
        }
        $order_id = $stmt_order->insert_id;
        $stmt_order->close();

        $total_price_calculated = 0;
        // Thêm từng sản phẩm vào order_items
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = (int)$product_ids[$i];
            $quantity = (int)$quantities[$i];

            if ($product_id > 0 && $quantity > 0) {
                // Lấy thông tin sản phẩm để tính giá
                $product = find_by_id('products', $product_id);
                if ($product) {
                    $price = $product['sale_price']; // Giả sử bảng 'products' có cột 'sale_price'
                    $total_price_calculated += $price * $quantity;

                    $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                    if (!$stmt_item->execute()) {
                        error_log("save_order.php - Lỗi khi thêm sản phẩm vào đơn hàng (ID sản phẩm: $product_id): " . $stmt_item->error);
                        throw new Exception("Lỗi khi thêm sản phẩm vào đơn hàng (ID sản phẩm: $product_id): " . $stmt_item->error);
                    }
                } else {
                    error_log("save_order.php - Lỗi: Không tìm thấy sản phẩm có ID: " . $product_id);
                    throw new Exception("Lỗi: Không tìm thấy sản phẩm có ID: " . $product_id);
                }
            }
        }
        $stmt_item->close();

        // Cập nhật tổng giá trị đơn hàng vào bảng orders
        $stmt_update_order = $conn->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
        $stmt_update_order->bind_param("di", $total_price_calculated, $order_id);
        if (!$stmt_update_order->execute()) {
            error_log("save_order.php - Lỗi khi cập nhật tổng giá đơn hàng: " . $stmt_update_order->error);
            throw new Exception("Lỗi khi cập nhật tổng giá đơn hàng: " . $stmt_update_order->error);
        }
        $stmt_update_order->close();

        // Cập nhật số lượng sản phẩm trong kho
        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id_to_update = (int)$product_ids[$i];
            $quantity_ordered = (int)$quantities[$i];

            if ($product_id_to_update > 0 && $quantity_ordered > 0) {
                // Lấy số lượng hiện tại trong kho
                $product_info = find_by_id('products', $product_id_to_update);
                if ($product_info) {
                    $current_quantity = (int)$product_info['quantity'];
                    if ($current_quantity >= $quantity_ordered) {
                        // Cập nhật số lượng trong kho
                        $sql_update_stock = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
                        $sql_update_stock->bind_param("ii", $quantity_ordered, $product_id_to_update);
                        if (!$sql_update_stock->execute()) {
                            error_log("save_order.php - Lỗi khi cập nhật kho cho sản phẩm ID $product_id_to_update: " . $sql_update_stock->error);
                            throw new Exception("Lỗi khi cập nhật kho cho sản phẩm ID $product_id_to_update: " . $sql_update_stock->error);
                        }
                        $sql_update_stock->close();
                    } else {
                        error_log("save_order.php - Lỗi: Số lượng đặt hàng cho sản phẩm ID $product_id_to_update vượt quá số lượng trong kho.");
                        throw new Exception("Lỗi: Số lượng đặt hàng cho sản phẩm ID $product_id_to_update vượt quá số lượng trong kho.");
                    }
                } else {
                    error_log("save_order.php - Lỗi: Không tìm thấy sản phẩm có ID $product_id_to_update để cập nhật kho.");
                    throw new Exception("Lỗi: Không tìm thấy sản phẩm có ID $product_id_to_update để cập nhật kho.");
                }
            }
        }

        // Nếu mọi thứ thành công, commit transaction
        $conn->commit();
        header("Location: order_detail.php?id=$order_id");
        exit();

    } catch (Exception $e) {
        // Nếu có lỗi, rollback transaction
        $conn->rollback();
        die("Lỗi xử lý đơn hàng: " . $e->getMessage());
    }
} else {
    die("Lỗi: Phương thức không hợp lệ.");
}
?>