<?php
require_once('includes/load.php');
page_require_level(3);

// Hàm lấy giá bán gần nhất theo ngày đặt hàng
function get_latest_sale_price($product_id, $order_date) {
    global $db;
    $product_id = (int)$product_id;
    $escaped_date = $db->escape($order_date);

    $sql = "SELECT gia FROM gia_ban 
            WHERE product_id = {$product_id} 
              AND ngay_ap_dung <= '{$escaped_date}' 
            ORDER BY ngay_ap_dung DESC 
            LIMIT 1";

    $result = $db->fetch_assoc($db->query($sql));
    return $result ? (float)$result['gia'] : 0;
}

// Kiểm tra nếu form đã được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'] ?? '';
    $order_date_raw = $_POST['order_date'] ?? '';
    $product_ids = $_POST['product_id'] ?? [];
    $quantities = $_POST['quantity'] ?? [];

    if (empty($customer_name) || empty($order_date_raw) || empty($product_ids)) {
        die("Lỗi: Vui lòng nhập đầy đủ thông tin đơn hàng.");
    }

    $conn = $db->get_connection();
    if (!$conn) {
        die("Lỗi: Không thể kết nối database.");
    }

    $conn->begin_transaction();
    $order_id = null;

    try {
        // Xử lý ngày đặt hàng
        $timestamp = strtotime($order_date_raw);
        if ($timestamp === false) {
            throw new Exception("Lỗi: Định dạng ngày đặt hàng không hợp lệ.");
        }
        $order_date = date('Y-m-d H:i:s', $timestamp);

        // Thêm đơn hàng vào bảng orders
        $stmt_order = $conn->prepare("INSERT INTO orders (customer_name, order_date) VALUES (?, ?)");
        $stmt_order->bind_param("ss", $customer_name, $order_date);
        if (!$stmt_order->execute()) {
            throw new Exception("Lỗi khi thêm đơn hàng: " . $stmt_order->error);
        }
        $order_id = $stmt_order->insert_id;
        $stmt_order->close();

        // Thêm từng sản phẩm vào order_items
        $total_price_calculated = 0;
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = (int)$product_ids[$i];
            $quantity = (int)$quantities[$i];

            if ($product_id > 0 && $quantity > 0) {
                $price = get_latest_sale_price($product_id, $order_date);
                if ($price <= 0) {
                    throw new Exception("Không tìm thấy giá bán hợp lệ cho sản phẩm ID {$product_id} vào ngày {$order_date}.");
                }

                $total_price_calculated += $price * $quantity;

                $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                if (!$stmt_item->execute()) {
                    throw new Exception("Lỗi khi thêm sản phẩm vào đơn hàng (ID sản phẩm: $product_id): " . $stmt_item->error);
                }
            }
        }
        $stmt_item->close();

        // Cập nhật tổng giá trị đơn hàng
        $stmt_update_order = $conn->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
        $stmt_update_order->bind_param("di", $total_price_calculated, $order_id);
        if (!$stmt_update_order->execute()) {
            throw new Exception("Lỗi khi cập nhật tổng giá đơn hàng: " . $stmt_update_order->error);
        }
        $stmt_update_order->close();

        // Cập nhật kho
        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id_to_update = (int)$product_ids[$i];
            $quantity_ordered = (int)$quantities[$i];

            if ($product_id_to_update > 0 && $quantity_ordered > 0) {
                $product_info = find_by_id('products', $product_id_to_update);
                if (!$product_info) {
                    throw new Exception("Không tìm thấy sản phẩm ID {$product_id_to_update}.");
                }

                $current_quantity = (int)$product_info['quantity'];
                if ($current_quantity < $quantity_ordered) {
                    throw new Exception("Số lượng đặt vượt quá tồn kho cho sản phẩm ID {$product_id_to_update}.");
                }

                $stmt_update_stock = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
                $stmt_update_stock->bind_param("ii", $quantity_ordered, $product_id_to_update);
                if (!$stmt_update_stock->execute()) {
                    throw new Exception("Lỗi khi cập nhật kho: " . $stmt_update_stock->error);
                }
                $stmt_update_stock->close();
            }
        }

        // Commit
        $conn->commit();
        header("Location: order_detail.php?id=$order_id");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Lỗi xử lý đơn hàng: " . $e->getMessage());
    }
} else {
    die("Lỗi: Phương thức không hợp lệ.");
}
?>
