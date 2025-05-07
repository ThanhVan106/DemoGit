<?php
  require_once('includes/load.php');
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(1); // Yêu cầu quyền truy cập mức 1 (quản trị viên hệ thống)
?>
<?php
  // Thực hiện xóa người dùng dựa trên ID của người dùng được truyền từ URL
  $delete_id = delete_by_id('users',(int)$_GET['id']);
  if($delete_id){
      $session->msg("s","Người dùng đã được xóa."); // Nếu xóa thành công, hiển thị thông báo thành công
      redirect('users.php'); // Chuyển hướng về trang quản lý người dùng
  } else {
      $session->msg("d","Xóa người dùng thất bại hoặc thiếu tham số."); // Nếu xóa thất bại hoặc thiếu tham số, hiển thị thông báo lỗi
      redirect('users.php'); // Chuyển hướng về trang quản lý người dùng
  }
?>
