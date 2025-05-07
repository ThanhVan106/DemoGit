<?php
  require_once('includes/load.php');
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(2); // Yêu cầu quyền truy cập mức 2 (quản trị viên)
?>
<?php
  // Tìm kiếm thông tin về phương tiện (media) dựa trên ID được truyền từ URL
  $find_media = find_by_id('media',(int)$_GET['id']);
  $photo = new Media(); // Tạo một đối tượng Media mới
  // Thực hiện xóa phương tiện (media) và tệp liên quan dựa trên ID và tên tệp của phương tiện
  if($photo->media_destroy($find_media['id'],$find_media['file_name'])){
      $session->msg("s","Ảnh đã được xóa."); // Nếu xóa thành công, hiển thị thông báo thành công
      redirect('media.php'); // Chuyển hướng về trang quản lý phương tiện
  } else {
      $session->msg("d","Xóa ảnh thất bại hoặc thiếu thông số."); // Nếu xóa thất bại hoặc thiếu thông số, hiển thị thông báo lỗi
      redirect('media.php'); // Chuyển hướng về trang quản lý phương tiện
  }
?>
