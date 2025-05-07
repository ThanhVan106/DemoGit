<?php
  $page_title = 'Chi tiết phiếu nhập'; // Tiêu đề trang
  require_once('includes/load.php');
  page_require_level(4);

  // Kiểm tra có tham số 'receipt_id' trong URL không
  if (isset($_GET['receipt_id']) && is_numeric($_GET['receipt_id'])) {
    $receipt_id = (int)$_GET['receipt_id'];

    // Truy vấn lấy thông tin phiếu nhập
    $sql_receipt = "SELECT * FROM receipts WHERE id = {$receipt_id}";
    $result_receipt = $db->query($sql_receipt);

    if ($result_receipt && $result_receipt->num_rows > 0) {
      $receipt = $result_receipt->fetch_assoc();

      // Truy vấn lấy chi tiết các sản phẩm trong phiếu nhập
      $sql_details = "SELECT ri.*, p.name AS product_name
                      FROM receipt_items ri
                      JOIN products p ON ri.product_id = p.id
                      WHERE ri.receipt_id = {$receipt_id}";
      $result_details = $db->query($sql_details);

    } else {
      // Nếu không tìm thấy phiếu nhập
      $session->msg("d", "Không tìm thấy phiếu nhập này.");
      redirect('stock_in_report.php');
    }
  } else {
    // Nếu không có tham số 'receipt_id' trong URL
    $session->msg("d", "Phiếu nhập không hợp lệ.");
    redirect('stock_in_report.php');
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-eye-open"></span> Chi tiết phiếu nhập</strong>
      </div>
      <div class="panel-body">
        <!-- Thông tin phiếu nhập -->
        <div class="well">
          <h4>Thông tin phiếu nhập</h4>
          <p><strong>Mã phiếu:</strong> <?php echo remove_junk($receipt['code']); ?></p>
          <p><strong>Ngày nhập:</strong> <?php echo date('d/m/Y', strtotime($receipt['receipt_date'])); ?></p>
          <p><strong>Nhà cung cấp:</strong> <?php echo remove_junk($receipt['supplier_name']); ?></p>
          <p><strong>Tổng tiền:</strong> <?php echo number_format($receipt['total_amount'], 2); ?> VNĐ</p>
          <p><strong>Ghi chú:</strong> <?php echo remove_junk($receipt['notes']); ?></p>
        </div>

        <!-- Chi tiết các sản phẩm trong phiếu nhập -->
        <h4>Chi tiết các sản phẩm trong phiếu nhập</h4>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tên sản phẩm</th>
              <th>Số lượng</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($result_details && $result_details->num_rows > 0) {
                while ($row = $result_details->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . remove_junk($row['product_name']) . "</td>";
                  echo "<td>" . (int)$row['quantity'] . "</td>";
                  echo "<td>" . number_format($row['unit_price'], 2) . " VNĐ</td>";
                  echo "<td>" . number_format($row['total_price'], 2) . " VNĐ</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='4' class='text-center'>Không có sản phẩm nào trong phiếu nhập này.</td></tr>";
              }
            ?>
          </tbody>
        </table>

        <a href="stock_in_report.php" class="btn btn-primary">Quay lại</a>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
