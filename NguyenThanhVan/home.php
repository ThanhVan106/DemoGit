<?php
  // Thiết lập tiêu đề trang
  $page_title = 'Trang Chủ';
  
  // Import các file cần thiết
  require_once('includes/load.php');
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $now = date('H:i:s - d/m/Y');

  // Nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
  if (!$session->isUserLoggedIn(true)) { 
    redirect('index.php', false);
  }
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <!-- Hiển thị thông báo -->
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
        <!-- Tiêu đề trang chủ -->
         <h1>Chào mừng người dùng <hr> Hệ thống Quản lý Bán hàng</h1>
         <p>Hãy duyệt qua để tìm các trang mà bạn có thể truy cập!</p>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
