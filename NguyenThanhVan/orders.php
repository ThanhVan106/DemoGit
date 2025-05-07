<?php
  $page_title = 'Tất cả đơn hàng';
  require_once('includes/load.php');
  page_require_level(3);

  $search = isset($_GET['search']) ? remove_junk($db->escape($_GET['search'])) : '';

  if ($search != '') {
    $orders = find_by_sql("SELECT * FROM orders WHERE customer_name LIKE '%{$search}%'");
  } else {
    $orders = find_all('orders');
  }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-md-4">
            <strong><span class="glyphicon glyphicon-th"></span> Tất cả đơn hàng</strong>
          </div>
          <div class="col-md-4 text-center">
            <form method="GET" action="orders.php" class="form-inline" style="display: inline-block;">
              <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên khách hàng" value="<?php echo isset($_GET['search']) ? remove_junk($_GET['search']) : ''; ?>">
              </div>
              <button type="submit" class="btn btn-primary">Tìm</button>
            </form>
          </div>
          <div class="col-md-4 text-right">
            <a href="add_order.php" class="btn btn-info">Thêm đơn hàng</a>
          </div>
        </div>
      </div>

      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Khách hàng</th>
              <th>Tổng tiền</th>
              <th>Ngày</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $i => $order): ?>
              <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo remove_junk($order['customer_name']); ?></td>
                <td><?php echo number_format($order['total_price'], 2); ?> đ</td>
                <td><?php echo date("Y-m-d", strtotime($order['order_date'])); ?></td>
                <td>
                  <a href="order_detail.php?id=<?php echo (int)$order['id']; ?>" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span>
                  </a>
                  <a href="edit_order.php?id=<?php echo (int)$order['id']; ?>" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="delete_order.php?id=<?php echo (int)$order['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Bạn có chắc chắn muốn xoá đơn hàng này?');">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?>
              <tr><td colspan="5" class="text-center">Không tìm thấy đơn hàng nào.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
