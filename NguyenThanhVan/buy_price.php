<?php
  $page_title = 'Tất cả giá nhập';
  require_once('includes/load.php');
  page_require_level(1);

  $sql = "
    SELECT ri.id, p.name AS product_name, ri.unit_price, r.receipt_date
    FROM receipt_items ri
    JOIN products p ON ri.product_id = p.id
    JOIN receipts r ON ri.receipt_id = r.id
    ORDER BY r.receipt_date DESC
  ";

  $all_prices = $db->query($sql);
  $all_prices = $db->while_loop($all_prices);
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-import"></span> Quản lý giá nhập</strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Sản phẩm</th>
              <th>Giá nhập</th>
              <th>Ngày nhập</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_prices as $i => $row): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= remove_junk($row['product_name']) ?></td>
                <td><?= number_format($row['unit_price'], 2) ?> VNĐ</td>
                <td><?= date('d/m/Y', strtotime($row['receipt_date'])) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($all_prices)): ?>
              <tr><td colspan="4" class="text-center">Không có dữ liệu.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
