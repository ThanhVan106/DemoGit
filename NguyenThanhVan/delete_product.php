<?php
  require_once('includes/load.php');
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(4); // Yêu cầu quyền truy cập mức 4
?>
<?php
  // Tìm kiếm thông tin về sản phẩm dựa trên ID được truyền từ URL
  $product = find_by_id('products',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","Thiếu id Sản phẩm."); // Nếu không tìm thấy sản phẩm, hiển thị thông báo lỗi và chuyển hướng về trang quản lý sản phẩm
    redirect('product.php');
  }
?>
<?php
  // Thực hiện xóa sản phẩm dựa trên ID của sản phẩm
  $delete_id = delete_by_id('products',(int)$product['id']);
  if($delete_id){
      $session->msg("s","Sản phẩm đã được xóa."); // Nếu xóa thành công, hiển thị thông báo thành công
      redirect('product.php'); // Chuyển hướng về trang quản lý sản phẩm
  } else {
      $session->msg("d","Xóa sản phẩm thất bại."); // Nếu xóa thất bại, hiển thị thông báo lỗi
      redirect('product.php'); // Chuyển hướng về trang quản lý sản phẩm
  }
?>
