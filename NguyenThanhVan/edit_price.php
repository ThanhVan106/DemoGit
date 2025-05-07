<?php
  require_once('includes/load.php');
  page_require_level(3);

  // Kiểm tra nếu có ID được truyền vào URL
  if (isset($_GET['id'])) {
    $gia_ban_id = (int)$_GET['id'];

    // Lấy thông tin giá bán hiện tại từ cơ sở dữ liệu
    $sql = "SELECT gia_ban.*, products.name FROM gia_ban ";
    $sql .= "JOIN products ON gia_ban.product_id = products.id ";
    $sql .= "WHERE gia_ban.id = $gia_ban_id";
    $price = $db->query($sql);
    $price = $db->fetch_assoc($price); // Lấy dữ liệu

    // Kiểm tra nếu không có dữ liệu giá bán
    if (!$price) {
      $session->msg('d', 'Không tìm thấy giá bán.');
      redirect('manage_price.php', false);
    }
  } else {
    $session->msg('d', 'ID không hợp lệ.');
    redirect('manage_price.php', false);
  }

  // Xử lý khi người dùng gửi form
  if (isset($_POST['submit'])) {
    $gia = $_POST['gia'];
    $ngay_ap_dung = $_POST['ngay_ap_dung'];

    // Cập nhật giá bán vào cơ sở dữ liệu
    $sql_update = "UPDATE gia_ban SET gia = '$gia', ngay_ap_dung = '$ngay_ap_dung' WHERE id = $gia_ban_id";

    if ($db->query($sql_update)) {
      $session->msg('s', 'Cập nhật giá bán thành công.');
      redirect('manage_price.php', false);
    } else {
      $session->msg('d', 'Lỗi khi cập nhật giá bán.');
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>Chỉnh sửa giá bán</strong>
      </div>
      <div class="panel-body">
        <form action="edit_price.php?id=<?= $price['id'] ?>" method="post">
          <div class="form-group">
            <label for="product_id">Sản phẩm:</label>
            <input type="text" class="form-control" value="<?= remove_junk($price['name']) ?>" disabled>
          </div>
          <div class="form-group">
            <label for="gia">Giá:</label>
            <input type="number" class="form-control" name="gia" value="<?= remove_junk($price['gia']) ?>" required>
          </div>
          <div class="form-group">
            <label for="ngay_ap_dung">Ngày áp dụng:</label>
            <input type="date" class="form-control" name="ngay_ap_dung" value="<?= $price['ngay_ap_dung'] ?>" required>
          </div>
          <button type="submit" name="submit" class="btn btn-success">Cập nhật</button>
          <a href="manage_price.php" class="btn btn-primary">Quay lại</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
