<?php
  $page_title = 'Đổi mật khẩu';
  require_once('includes/load.php');
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(3); // Yêu cầu cấp quyền truy cập mức 3 (người dùng cấp cao)
?>
<?php $user = current_user(); ?>
<?php
  if(isset($_POST['update'])){ // Nếu nút cập nhật được nhấn

    $req_fields = array('new-password','old-password','id' ); // Các trường bắt buộc
    validate_fields($req_fields); // Kiểm tra các trường bắt buộc

    if(empty($errors)){ // Nếu không có lỗi

             if(sha1($_POST['old-password']) !== current_user()['password'] ){ // Kiểm tra mật khẩu cũ có khớp không
               $session->msg('d', "Mật khẩu cũ không khớp"); // Thông báo lỗi nếu mật khẩu cũ không khớp
               redirect('change_password.php',false); // Chuyển hướng đến trang đổi mật khẩu
             }

            $id = (int)$_POST['id']; // Lấy id người dùng hiện tại
            $new = remove_junk($db->escape(sha1($_POST['new-password']))); // Lấy mật khẩu mới và mã hóa
            $sql = "UPDATE users SET password ='{$new}' WHERE id='{$db->escape($id)}'"; // Tạo câu lệnh SQL để cập nhật mật khẩu mới
            $result = $db->query($sql); // Thực thi câu lệnh SQL
                if($result && $db->affected_rows() === 1): // Nếu câu lệnh thực thi thành công và có 1 hàng bị ảnh hưởng
                  $session->logout(); // Đăng xuất người dùng
                  $session->msg('s',"Đăng nhập với mật khẩu mới của bạn."); // Thông báo thành công
                  redirect('index.php', false); // Chuyển hướng đến trang index
                else:
                  $session->msg('d',' Xin lỗi, không thể cập nhật!'); // Thông báo lỗi nếu không thể cập nhật mật khẩu mới
                  redirect('change_password.php', false); // Chuyển hướng đến trang đổi mật khẩu
                endif;
    } else {
      $session->msg("d", $errors); // Thông báo lỗi nếu có lỗi xảy ra trong quá trình kiểm tra trường bắt buộc
      redirect('change_password.php',false); // Chuyển hướng đến trang đổi mật khẩu
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Thay đổi mật khẩu của bạn</h3>
     </div>
     <?php echo display_msg($msg); ?> <!-- Hiển thị thông báo nếu có -->
      <form method="post" action="change_password.php" class="clearfix">
        <div class="form-group">
              <label for="newPassword" class="control-label">Mật khẩu mới</label>
              <input type="password" class="form-control" name="new-password" placeholder="Mật khẩu mới">
        </div>
        <div class="form-group">
              <label for="oldPassword" class="control-label">Mật khẩu cũ</label>
              <input type="password" class="form-control" name="old-password" placeholder="Mật khẩu cũ">
        </div>
        <div class="form-group clearfix">
               <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>"> <!-- Truyền id người dùng hiện tại dưới dạng hidden -->
                <button type="submit" name="update" class="btn btn-info">Thay đổi</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
