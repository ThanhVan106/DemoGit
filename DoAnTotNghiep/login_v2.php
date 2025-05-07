<?php
  // Bắt đầu bộ nhớ đệm đầu ra
  ob_start();
  
  // Import các file cần thiết
  require_once('includes/load.php');
  
  // Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
  if($session->isUserLoggedIn(true)) { 
    redirect('home.php', false);
  }
?>

<!-- Phần HTML cho trang đăng nhập -->
<div class="login-page">
    <div class="text-center">
       <!-- Tiêu đề chào mừng -->
       <h1>Chào mừng</h1>
       <!-- Phụ đề cho trang đăng nhập -->
       <p>Đăng nhập để bắt đầu phiên làm việc của bạn</p>
     </div>
     
     <!-- Hiển thị thông báo nếu có -->
     <?php echo display_msg($msg); ?>
     
     <!-- Form đăng nhập -->
     <form method="post" action="auth_v2.php" class="clearfix">
        <div class="form-group">
            <!-- Nhập tên người dùng -->
            <label for="username" class="control-label">Tên người dùng</label>
            <input type="text" class="form-control" name="username" placeholder="Tên người dùng">
        </div>
        <div class="form-group">
            <!-- Nhập mật khẩu -->
            <label for="Password" class="control-label">Mật khẩu</label>
            <input type="password" name= "password" class="form-control" placeholder="Mật khẩu">
        </div>
        <div class="form-group">
            <!-- Nút đăng nhập -->
            <button type="submit" class="btn btn-info  pull-right">Đăng nhập</button>
        </div>
    </form>
</div>

<?php include_once('layouts/header.php'); ?>
