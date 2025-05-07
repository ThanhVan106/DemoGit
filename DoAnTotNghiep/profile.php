<?php
  // Đặt tiêu đề trang
  $page_title = 'Hồ sơ của tôi';

  // Import các file cần thiết
  require_once('includes/load.php');

  // Kiểm tra quyền truy cập của người dùng để xem trang này
  page_require_level(3);
?>

<?php
  // Lấy ID của người dùng từ tham số truyền vào
  $user_id = (int)$_GET['id'];

  // Kiểm tra xem ID có tồn tại không
  if(empty($user_id)):
    // Nếu không tồn tại, chuyển hướng về trang chủ
    redirect('home.php', false);
  else:
    // Nếu tồn tại, lấy thông tin người dùng
    $user_p = find_by_id('users', $user_id);
  endif;
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-4">
       <div class="panel profile">
         <div class="jumbotron text-center bg-red">
            <!-- Hiển thị ảnh đại diện của người dùng -->
            <img class="img-circle img-size-2" src="uploads/users/<?php echo $user_p['image'];?>" alt="">
            <!-- Hiển thị tên của người dùng -->
            <h3><?php echo first_character($user_p['name']); ?></h3>
         </div>
        <?php if( $user_p['id'] === $user['id']):?>
         <!-- Nếu đây là hồ sơ của người dùng hiện tại, hiển thị nút chỉnh sửa -->
         <ul class="nav nav-pills nav-stacked">
          <li><a href="edit_account.php"> <i class="glyphicon glyphicon-edit"></i> Chỉnh sửa hồ sơ</a></li>
         </ul>
       <?php endif;?>
       </div>
   </div>
</div>

<?php include_once('layouts/footer.php'); ?>
