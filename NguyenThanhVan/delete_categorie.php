<?php
  require_once('includes/load.php');
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(4); // Yêu cầu quyền truy cập mức 1 (người dùng cơ bản)
?>
<?php
  // Lấy thông tin của danh mục dựa trên ID được truyền từ URL
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","Thiếu ID danh mục."); // Nếu không tìm thấy danh mục, hiển thị thông báo lỗi
    redirect('categorie.php'); // Chuyển hướng về trang danh sách danh mục
  }
?>
<?php
  // Xóa danh mục dựa trên ID đã được truyền từ URL
  $delete_id = delete_by_id('categories',(int)$categorie['id']);
  if($delete_id){
      $session->msg("s","Danh mục đã được xóa."); // Nếu xóa thành công, hiển thị thông báo thành công
      redirect('categorie.php'); // Chuyển hướng về trang danh sách danh mục
  } else {
      $session->msg("d","Xóa danh mục thất bại."); // Nếu xóa thất bại, hiển thị thông báo lỗi
      redirect('categorie.php'); // Chuyển hướng về trang danh sách danh mục
  }
?>
