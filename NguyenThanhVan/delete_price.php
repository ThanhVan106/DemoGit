<?php
  // Bao gồm các file cần thiết
  require_once('includes/load.php');
  page_require_level(4); // Kiểm tra quyền truy cập của người dùng

  // Kết nối cơ sở dữ liệu
  $conn = mysqli_connect("localhost", "root", "", "sales_system2");

  // Kiểm tra kết nối
  if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
  }

  // Biến để lưu thông báo thành công hoặc lỗi
  $success_message = '';
  $error_message = '';

  // Kiểm tra nếu có ID được gửi đến
  if (isset($_GET['id'])) {
    $gia_ban_id = intval($_GET['id']); // Lấy ID và ép kiểu thành số nguyên

    // Truy vấn để kiểm tra xem bản ghi có tồn tại hay không
    $sql_check = "SELECT id FROM gia_ban WHERE id = $gia_ban_id";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
      // Truy vấn để xóa bản ghi trong bảng gia_ban
      $sql_delete = "DELETE FROM gia_ban WHERE id = $gia_ban_id";

      if (mysqli_query($conn, $sql_delete)) {
        // Nếu xóa thành công
        $success_message = "Đã xóa giá bán thành công.";
      } else {
        // Nếu có lỗi xảy ra
        $error_message = "Lỗi khi xóa giá bán: " . mysqli_error($conn);
      }
    } else {
      // Nếu không tìm thấy bản ghi với ID
      $error_message = "ID giá bán không hợp lệ.";
    }
  } else {
    // Nếu không có ID được gửi đến
    $error_message = "ID giá bán không hợp lệ.";
  }

  // Đóng kết nối
  mysqli_close($conn);

  // Hiển thị thông báo thành công hoặc lỗi
  include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-12">
    <?php if (isset($success_message)): ?>
      <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
      <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <a href="manage_price.php" class="btn btn-primary">Quay lại danh sách giá bán</a>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
