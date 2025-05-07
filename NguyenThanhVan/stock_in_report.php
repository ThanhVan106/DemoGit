<?php
  $page_title = 'Báo cáo nhập kho';
  require_once('includes/load.php');
  page_require_level(4);

  // Lọc theo từ khóa chung (mã phiếu hoặc nhà cung cấp)
  $keyword = isset($_GET['keyword']) ? $db->escape($_GET['keyword']) : '';

  $sql_receipts = "SELECT * FROM receipts WHERE 1=1";
  if (!empty($keyword)) {
    $sql_receipts .= " AND (code LIKE '%{$keyword}%' OR supplier_name LIKE '%{$keyword}%')";
  }
  $sql_receipts .= " ORDER BY receipt_date DESC";

  $result_receipts = $db->query($sql_receipts);
  $receipts = [];
  if ($result_receipts && $result_receipts->num_rows > 0) {
    while ($row = $result_receipts->fetch_assoc()) {
      $receipts[] = $row;
    }
  }

  // Xử lý xóa phiếu nhập
  if (isset($_GET['delete_receipt_id'])) {
    $receipt_id_to_delete = (int)$_GET['delete_receipt_id'];

    $receipt_to_delete = find_by_id('receipts', $receipt_id_to_delete);
    if ($receipt_to_delete) {
      $delete_items_sql = "DELETE FROM receipt_items WHERE receipt_id = {$receipt_id_to_delete}";
      $db->query($delete_items_sql);

      $delete_receipt_sql = "DELETE FROM receipts WHERE id = {$receipt_id_to_delete}";
      $delete_receipt = $db->query($delete_receipt_sql);

      if ($delete_receipt) {
        $session->msg("s", "Đã xóa phiếu nhập thành công.");
      } else {
        $session->msg("d", "Xóa phiếu nhập thất bại.");
      }
    } else {
      $session->msg("d", "Phiếu nhập không tồn tại.");
    }
    redirect('stock_in_report.php');
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th-list"></span> Báo cáo nhập kho</strong>
        <form method="GET" action="stock_in_report.php" class="form-inline" style="margin-top: 10px; text-align: center;">
          <input type="text" name="keyword" class="form-control input-sm" placeholder="Tìm theo mã phiếu hoặc nhà cung cấp..." value="<?= htmlspecialchars($keyword) ?>" style="width: 300px;">
          <button type="submit" class="btn btn-primary btn-sm">Tìm</button>
        </form>
      </div>

      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Mã phiếu</th>
              <th>Ngày nhập</th>
              <th>Nhà cung cấp</th>
              <th>Tổng tiền</th>
              <th>Ghi chú</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($receipts as $receipt): ?>
              <tr>
                <td><?php echo $receipt['id']; ?></td>
                <td><?php echo remove_junk($receipt['code']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($receipt['receipt_date'])); ?></td>
                <td><?php echo remove_junk($receipt['supplier_name']); ?></td>
                <td><?php echo number_format($receipt['total_amount'], 2); ?> VNĐ</td>
                <td><?php echo remove_junk($receipt['notes']); ?></td>
                <td>
                  <a href="view_receipt.php?receipt_id=<?php echo $receipt['id']; ?>" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span>
                  </a>
                  <a href="edit_receipt.php?receipt_id=<?php echo $receipt['id']; ?>" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="stock_in_report.php?delete_receipt_id=<?php echo $receipt['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu nhập này?');">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
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
