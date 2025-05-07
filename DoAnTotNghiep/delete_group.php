<?php
  require_once('includes/load.php');
  // Kiểm tra quyền truy cập của người dùng
   page_require_level(1); // Yêu cầu quyền truy cập mức 1 (người dùng)
?>
<?php
  // Xóa nhóm dựa trên ID được truyền từ URL
  $delete_id = delete_by_id('user_groups',(int)$_GET['id']);
  if($delete_id){
      $session->msg("s","Nhóm đã được xóa."); // Nếu xóa thành công, hiển thị thông báo thành công
      redirect('group.php'); // Chuyển hướng về trang danh sách nhóm
  } else {
      $session->msg("d","Xóa nhóm thất bại hoặc thiếu thông số."); // Nếu xóa thất bại hoặc thiếu thông số, hiển thị thông báo lỗi
      redirect('group.php'); // Chuyển hướng về trang danh sách nhóm
  }
?>
