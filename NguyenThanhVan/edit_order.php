<?php
$page_title = 'Chỉnh sửa đơn hàng';
require_once('includes/load.php');
page_require_level(3);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $session->msg('d', 'ID đơn hàng không hợp lệ.');
    redirect('orders.php');
}
$order_id = (int)$_GET['id'];

$order = find_by_id('orders', $order_id);
if (!$order) {
    $session->msg('d', 'Không tìm thấy đơn hàng.');
    redirect('orders.php');
}

$order_items = find_by_sql("SELECT oi.*, p.name AS product_name FROM order_items oi
                            JOIN products p ON oi.product_id = p.id
                            WHERE oi.order_id = {$order_id}");

$all_products = find_all('products');

if (isset($_GET['remove_item']) && is_numeric($_GET['remove_item'])) {
    $remove_item_id = (int)$_GET['remove_item'];
    $sql_delete_item = "DELETE FROM order_items WHERE id = {$remove_item_id} AND order_id = {$order_id}";
    if ($db->query($sql_delete_item)) {
        $session->msg('s', 'Đã xóa sản phẩm khỏi đơn hàng.');
    } else {
        $session->msg('d', 'Không thể xóa sản phẩm khỏi đơn hàng.');
    }
    redirect('edit_order.php?id=' . $order_id, false);
}

if (isset($_POST['add_product_to_order'])) {
    if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        $existing = find_by_sql("SELECT id FROM order_items WHERE order_id = {$order_id} AND product_id = {$product_id}");
        if ($existing) {
            $session->msg('w', 'Sản phẩm đã có trong đơn hàng.');
        } else {
            $product = find_by_id('products', $product_id);
            if ($product) {
                $price = (float)$product['sale_price'];
                $sql_add = "INSERT INTO order_items (order_id, product_id, qty, price) VALUES ({$order_id}, {$product_id}, {$quantity}, {$price})";
                if ($db->query($sql_add)) {
                    $session->msg('s', 'Đã thêm sản phẩm.');
                } else {
                    $session->msg('d', 'Không thể thêm sản phẩm.');
                }
            }
        }
    } else {
        $session->msg('d', 'Chọn sản phẩm và số lượng hợp lệ.');
    }
    redirect('edit_order.php?id=' . $order_id, false);
}

if (isset($_POST['update_order'])) {
    $req_fields = array('customer_name', 'order_date');
    validate_fields($req_fields);

    if (empty($errors)) {
        $customer_name = remove_junk($db->escape($_POST['customer_name']));
        $order_date = remove_junk($db->escape($_POST['order_date']));
        $sql = "UPDATE orders SET customer_name='{$customer_name}', order_date='{$order_date}' WHERE id='{$order_id}'";
        $result = $db->query($sql);

        if ($result) {
            if (isset($_POST['qty'], $_POST['price'])) {
                foreach ($_POST['qty'] as $item_id => $qty) {
                    $qty = (int)$qty;
                    $price = (float)$_POST['price'][$item_id];
                    $sql_update = "UPDATE order_items SET qty='{$qty}', price='{$price}' WHERE id='{$item_id}' AND order_id='{$order_id}'";
                    $db->query($sql_update);
                }
            }

            // 🔧 TÍNH LẠI total_price
            $res = $db->query("SELECT SUM(qty * price) AS total FROM order_items WHERE order_id = '{$order_id}'");
            $row = $db->fetch_assoc($res);
            $new_total = isset($row['total']) ? (float)$row['total'] : 0;

            // 🔁 CẬP NHẬT total_price
            $db->query("UPDATE orders SET total_price = '{$new_total}' WHERE id = '{$order_id}'");

            $session->msg('s', 'Đơn hàng đã cập nhật.');
            redirect('orders.php');
        } else {
            $session->msg('d', 'Cập nhật thất bại.');
            redirect('edit_order.php?id=' . $order_id, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_order.php?id=' . $order_id, false);
    }
}

?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
        <form method="post" action="edit_order.php?id=<?php echo $order_id; ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Chỉnh sửa đơn hàng #<?php echo $order_id; ?></strong>
                    <a href="orders.php" class="btn btn-info pull-right">Quay lại</a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tên khách hàng</label>
                            <input type="text" name="customer_name" class="form-control" value="<?php echo remove_junk($order['customer_name']); ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Ngày đặt hàng</label>
                            <input type="text" name="order_date" class="form-control" value="<?php echo $order['order_date']; ?>">
                        </div>
                    </div>

                    <table class="table table-bordered" id="order-items-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá (đ)</th>
                                <th>Thành tiền (đ)</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $i => $item): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo remove_junk($item['product_name']); ?></td>
                                    <td><input type="number" class="form-control qty-input" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['qty']; ?>" min="1"></td>
                                    <td><input type="number" class="form-control price-input" name="price[<?php echo $item['id']; ?>]" value="<?php echo $item['price']; ?>" step="0.01"></td>
                                    <td class="total-cell"><?php echo number_format($item['qty'] * $item['price'], 0); ?> đ</td>
                                    <td><a href="edit_order.php?id=<?php echo $order_id; ?>&remove_item=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
                                <td id="order-total" colspan="2"><strong>0 đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="submit" name="update_order" class="btn btn-primary">Cập nhật đơn hàng</button>
                </div>
            </div>
        </form>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Thêm sản phẩm</strong></div>
            <div class="panel-body">
                <form method="post" action="edit_order.php?id=<?php echo $order_id; ?>">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <select name="product_id" class="form-control">
                                <option value="">Chọn sản phẩm</option>
                                <?php foreach ($all_products as $product): ?>
                                    <option value="<?php echo $product['id']; ?>"><?php echo remove_junk($product['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="number" name="quantity" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-3 form-group">
                            <button type="submit" name="add_product_to_order" class="btn btn-success">Thêm sản phẩm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function updateRowTotal(row) {
        const qty = parseFloat(row.querySelector(".qty-input").value) || 0;
        const price = parseFloat(row.querySelector(".price-input").value) || 0;
        const total = qty * price;
        row.querySelector(".total-cell").textContent = total.toLocaleString('vi-VN') + " đ";
        return total;
    }

    function updateOrderTotal() {
        let grandTotal = 0;
        document.querySelectorAll("#order-items-table tbody tr").forEach(row => {
            grandTotal += updateRowTotal(row);
        });
        document.getElementById("order-total").innerHTML = "<strong>" + grandTotal.toLocaleString('vi-VN') + " đ</strong>";
    }

    document.querySelectorAll(".qty-input, .price-input").forEach(input => {
        input.addEventListener("input", updateOrderTotal);
    });

    updateOrderTotal();
});
</script>

<?php include_once('layouts/footer.php'); ?>
