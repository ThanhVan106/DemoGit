<?php
  // Đặt tiêu đề trang
  $page_title = 'Chỉnh sửa tài khoản';

  // Đưa vào tệp load.php để tải các tài nguyên cần thiết
  require_once('includes/load.php');

  // Yêu cầu mức quyền truy cập là 3 (quản lý)
  page_require_level(3);
?>

<?php
// Xử lý việc tải ảnh lên cho người dùng
  if(isset($_POST['submit'])) {
    $photo = new Media(); // Khởi tạo đối tượng Media
    $user_id = (int)$_POST['user_id']; // Lấy ID của người dùng từ biểu mẫu
    $photo->upload($_FILES['file_upload']); // Tải ảnh lên
    if($photo->process_user($user_id)){
      $session->msg('s','Ảnh đã được tải lên.'); // Thông báo thành công
      redirect('edit_account.php'); // Chuyển hướng đến trang chỉnh sửa tài khoản
    } else{
      $session->msg('d',join($photo->errors)); // Thông báo lỗi nếu có
      redirect('edit_account.php'); // Chuyển hướng đến trang chỉnh sửa tài khoản
    }
  }
?>

<?php
 // Xử lý việc cập nhật thông tin của người dùng
  if(isset($_POST['update'])){
    $req_fields = array('name','username' ); // Các trường bắt buộc cần nhập
    validate_fields($req_fields); // Kiểm tra xem các trường đã nhập đầy đủ chưa
    if(empty($errors)){
      $id = (int)$_SESSION['user_id']; // Lấy ID của người dùng hiện tại từ phiên đăng nhập
      $name = remove_junk($db->escape($_POST['name'])); // Lấy và loại bỏ ký tự đặc biệt trong tên
      $username = remove_junk($db->escape($_POST['username'])); // Lấy và loại bỏ ký tự đặc biệt trong tên đăng nhập
      $sql = "UPDATE users SET name ='{$name}', username ='{$username}' WHERE id='{$id}'"; // Tạo câu truy vấn cập nhật thông tin người dùng
      $result = $db->query($sql); // Thực thi câu truy vấn
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Tài khoản đã được cập nhật."); // Thông báo cập nhật thành công
        redirect('edit_account.php', false); // Chuyển hướng đến trang chỉnh sửa tài khoản
      } else {
        $session->msg('d','Xin lỗi, cập nhật thất bại!'); // Thông báo cập nhật thất bại
        redirect('edit_account.php', false); // Chuyển hướng đến trang chỉnh sửa tài khoản
      }
    } else {
      $session->msg("d", $errors); // Thông báo lỗi nếu các trường không hợp lệ
      redirect('edit_account.php',false); // Chuyển hướng đến trang chỉnh sửa tài khoản
    }
  }
?>

<?php include_once('layouts/header.php'); ?> <!-- Đưa vào phần tiêu đề của trang -->

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?> <!-- Hiển thị thông báo -->
  </div>

  <!-- Phần chỉnh sửa ảnh của người dùng -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-heading clearfix">
          <span class="glyphicon glyphicon-camera"></span>
          <span>Thay đổi ảnh của tôi</span>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <img class="img-circle img-size-2" src="uploads/users/<?php echo $user['image'];?>" alt=""> <!-- Hiển thị ảnh người dùng hiện tại -->
          </div>
          <div class="col-md-8">
            <form class="form" action="edit_account.php" method="POST" enctype="multipart/form-data"> <!-- Biểu mẫu thay đổi ảnh -->
              <div class="form-group">
                <input type="file" name="file_upload" multiple="multiple" class="btn btn-default btn-file"/> <!-- Chọn ảnh từ thiết bị -->
              </div>
              <div class="form-group">
                <input type="hidden" name="user_id" value="<?php echo $user['id'];?>"> <!-- ID của người dùng -->
                <button type="submit" name="submit" class="btn btn-warning">Thay đổi</button> <!-- Nút thay đổi -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Phần chỉnh sửa thông tin người dùng khác -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-edit"></span>
        <span>Chỉnh sửa tài khoản của tôi</span>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_account.php?id=<?php echo (int)$user['id'];?>" class="clearfix"> <!-- Biểu mẫu chỉnh sửa thông tin người dùng -->
<div class="form-group">
<label for="name" class="control-label">Tên</label>
<input type="name" class="form-control" name="name" value="<?php echo remove_junk(ucwords($user['name'])); ?>"> <!-- Trường nhập tên -->
</div>
<div class="form-group">
<label for="username" class="control-label">Tên đăng nhập</label>
<input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($user['username'])); ?>"> <!-- Trường nhập tên đăng nhập -->
</div>
<div class="form-group clearfix">
<a href="change_password.php" title="Đổi mật khẩu" class="btn btn-danger pull-right">Đổi mật khẩu</a> <!-- Link chuyển hướng đến trang đổi mật khẩu -->
<button type="submit" name="update" class="btn btn-info">Cập nhật</button> <!-- Nút cập nhật thông tin -->
</div>
</form>
</div>
</div>

  </div>
</div>
<?php include_once('layouts/footer.php'); ?> <!-- Đưa vào phần chân trang -->
