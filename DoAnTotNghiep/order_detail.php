<?php
  $page_title = 'Chi tiết đơn hàng';
  require_once('includes/load.php');
  page_require_level(3);

  // Lấy ID đơn hàng từ URL
  $order_id = (int)$_GET['id'];

  // Kiểm tra nếu đơn hàng tồn tại
  $order = find_by_id('orders', $order_id);
  if (!$order) {
    $session->msg('d', 'Không tìm thấy đơn hàng.');
    redirect('orders.php');
  }

  // Lấy thông tin sản phẩm trong đơn hàng
  $order_items = find_by_sql("SELECT oi.*, p.name AS product_name, p.sale_price AS unit_price 
                               FROM order_items oi 
                               JOIN products p ON oi.product_id = p.id 
                               WHERE oi.order_id = {$order_id}");
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span> 
          CHI TIẾT ĐƠN HÀNG #<?php echo $order_id; ?>
        </strong>
        <a href="orders.php" class="btn btn-info pull-right">QUAY LẠI DANH SÁCH ĐƠN HÀNG</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Tên sản phẩm</th>
              <th>Số lượng</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
            </tr>
          </thead>
          <tbody>
            <?php $total_price = 0; ?>
            <?php if(!empty($order_items)): ?>
              <?php foreach ($order_items as $i => $item): ?>
                <tr>
                  <td><?php echo $i + 1; ?></td>
                  <td><?php echo remove_junk($item['product_name']); ?></td>
                  <td><?php echo (int)$item['qty']; ?></td>
                  <td><?php echo number_format($item['unit_price'], 0); ?> đ</td>
                  <td>
                    <?php 
                      $subtotal = $item['unit_price'] * $item['qty'];
                      echo number_format($subtotal, 0); 
                      $total_price += $subtotal;
                    ?> đ
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Không có sản phẩm nào trong đơn hàng này</td>
              </tr>
            <?php endif; ?>
            <tr>
              <td colspan="4" class="text-right"><strong>Tổng tiền:</strong></td>
              <td><?php echo number_format($total_price, 0); ?> đ</td>
            </tr>
          </tbody>
        </table>

        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>THÔNG TIN ĐƠN HÀNG</strong>
          </div>
          <div class="panel-body">
            <p><strong>Khách hàng:</strong> 
              <?php echo !empty($order['customer_name']) ? remove_junk($order['customer_name']) : 'Không xác định'; ?>
            </p>
            <p><strong>Ngày đặt hàng:</strong> 
              <?php echo isset($order['order_date']) ? date('d/m/Y H:i', strtotime($order['order_date'])) : 'Không xác định'; ?>
            </p>
            <?php if(isset($order['notes']) && !empty($order['notes'])): ?>
              <p><strong>Ghi chú:</strong> <?php echo remove_junk($order['notes']); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
