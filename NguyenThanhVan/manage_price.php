<?php
$page_title = 'Tất cả giá bán';
  require_once('includes/load.php');
  page_require_level(3);
  $sql  = "SELECT gia_ban.*, products.name FROM gia_ban ";
$sql .= "JOIN products ON gia_ban.product_id = products.id ";
$sql .= "ORDER BY ngay_ap_dung DESC";
$all_prices = $db->query($sql);
$all_prices = $db->while_loop($all_prices); // Chuyển kết quả thành mảng

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-usd"></span>
          <span>Quản lý giá bán</span>
        </strong>
        <a href="add_price.php" class="btn btn-primary btn-sm pull-right">Thêm giá mới</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Sản phẩm</th>
              <th>Giá</th>
              <th>Ngày áp dụng</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_prices as $i => $row): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= remove_junk($row['name']) ?></td>
                <td><?= number_format($row['gia']) ?> đ</td>
                <td><?= date("d/m/Y", strtotime($row['ngay_ap_dung'])) ?></td>
                <td>
                <a href="edit_price.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-xs">Chỉnh sửa</a>
                <a href="delete_price.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('Xóa giá này?');">Xóa</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
