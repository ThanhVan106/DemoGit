<?php
  $page_title = 'Chỉnh sửa phiếu nhập';
  require_once('includes/load.php');
  page_require_level(4);

  // Lấy thông tin phiếu nhập từ cơ sở dữ liệu
  if (isset($_GET['receipt_id'])) {
    $receipt_id = (int)$_GET['receipt_id'];
    $receipt = find_by_id('receipts', $receipt_id);

    // Lấy chi tiết sản phẩm của phiếu nhập
    $receipt_items = [];
    $sql_items = "SELECT ri.*, p.name AS product_name
                  FROM receipt_items ri
                  INNER JOIN products p ON ri.product_id = p.id
                  WHERE ri.receipt_id = {$receipt_id}";
    $result_items = $db->query($sql_items);
    if ($result_items && $result_items->num_rows > 0) {
      while ($item = $result_items->fetch_assoc()) {
        $receipt_items[] = $item;
      }
    }
  }

  if (isset($_POST['update_receipt'])) {
    // Lấy dữ liệu từ form
    $supplier_name = remove_junk($db->escape($_POST['supplier_name']));
    $receipt_date = remove_junk($db->escape($_POST['receipt_date']));
    $notes = remove_junk($db->escape($_POST['notes']));

    // Cập nhật phiếu nhập
    $sql = "UPDATE receipts SET 
            supplier_name = '{$supplier_name}',
            receipt_date = '{$receipt_date}',
            notes = '{$notes}' 
            WHERE id = '{$receipt_id}'";

    if ($db->query($sql)) {
      $session->msg("s", "Cập nhật phiếu nhập thành công.");
      redirect('stock_in_report.php');
    } else {
      $session->msg("d", "Cập nhật phiếu nhập thất bại.");
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-edit"></span> Chỉnh sửa phiếu nhập</strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_receipt.php?receipt_id=<?php echo $receipt['id']; ?>">
          <!-- Nhà cung cấp -->
          <div class="form-group">
            <label for="supplier_name">Nhà cung cấp:</label>
            <input type="text" class="form-control" name="supplier_name" value="<?php echo remove_junk($receipt['supplier_name']); ?>" required>
          </div>

          <!-- Ngày nhập -->
          <div class="form-group">
            <label for="receipt_date">Ngày nhập:</label>
            <input type="date" class="form-control" name="receipt_date" value="<?php echo date('Y-m-d', strtotime($receipt['receipt_date'])); ?>" required>
          </div>

          <!-- Ghi chú -->
          <div class="form-group">
            <label for="notes">Ghi chú:</label>
            <textarea class="form-control" name="notes" rows="3"><?php echo remove_junk($receipt['notes']); ?></textarea>
          </div>

          <button type="submit" name="update_receipt" class="btn btn-primary">Cập nhật</button>
          <a href="stock_in_report.php" class="btn btn-default">Quay lại</a>
        </form>

        <hr>

        <!-- Bảng chi tiết sản phẩm -->
        <h4>Chi tiết sản phẩm</h4>
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
            <?php foreach ($receipt_items as $index => $item): ?>
              <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo remove_junk($item['product_name']); ?></td>
                <td><?php echo (int)$item['quantity']; ?></td>
                <td><?php echo number_format($item['unit_price'], 2); ?> VNĐ</td>
                <td><?php echo number_format($item['quantity'] * $item['unit_price'], 2); ?> VNĐ</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
