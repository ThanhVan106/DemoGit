<?php
require_once('includes/load.php');
page_require_level(33);

$order_id = (int)$_GET['id'];
if (!$order_id) {
  die("Thiếu ID đơn hàng.");
}

$con = $db->get_connection();

// Xoá các item của đơn hàng trước
$stmt = $con->prepare("DELETE FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->close();

// Xoá đơn hàng
$stmt = $con->prepare("DELETE FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->close();

// Thông báo và chuyển hướng
$session->msg("s", "Đã xoá đơn hàng.");
header("Location: orders.php");  // Quay lại trang orders.php
exit(); // Dừng lại để đảm bảo chuyển hướng chính xác
?>
