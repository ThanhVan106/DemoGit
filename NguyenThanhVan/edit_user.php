<?php
  // Thiết lập tiêu đề trang
  $page_title = 'Chỉnh sửa Người dùng';
  // Nạp các tệp tin cần thiết
  require_once('includes/load.php');
  // Kiểm tra quyền hạn của người dùng để xem trang này
  page_require_level(1);
?>
<?php
  // Tìm thông tin của người dùng dựa trên id
  $e_user = find_by_id('users',(int)$_GET['id']);
  // Lấy danh sách các nhóm người dùng
  $groups  = find_all('user_groups');
  // Nếu không tìm thấy người dùng, thông báo lỗi và chuyển hướng về trang danh sách người dùng
  if(!$e_user){
    $session->msg("d","Không có thông tin người dùng.");
    redirect('users.php');
  }
?>

<?php
// Cập nhật thông tin cơ bản của người dùng
  if(isset($_POST['update'])) {
    // Kiểm tra các trường bắt buộc
    $req_fields = array('name','username','level');
    validate_fields($req_fields);
    // Nếu không có lỗi, tiếp tục xử lý
    if(empty($errors)){
      $id = (int)$e_user['id'];
      $name = remove_junk($db->escape($_POST['name']));
      $username = remove_junk($db->escape($_POST['username']));
      $level = (int)$db->escape($_POST['level']);
      $status   = remove_junk($db->escape($_POST['status']));
      // Câu truy vấn để cập nhật thông tin người dùng
      $sql = "UPDATE users SET name ='{$name}', username ='{$username}',user_level='{$level}',status='{$status}' WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);
      // Nếu cập nhật thành công
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Cập nhật tài khoản thành công.");
        redirect('edit_user.php?id='.(int)$e_user['id'], false);
      } else {
        // Nếu cập nhật không thành công
        $session->msg('d','Lỗi khi cập nhật!');
        redirect('edit_user.php?id='.(int)$e_user['id'], false);
      }
    } else {
      // Nếu có lỗi, hiển thị thông báo lỗi và chuyển hướng trở lại trang chỉnh sửa người dùng
      $session->msg("d", $errors);
      redirect('edit_user.php?id='.(int)$e_user['id'],false);
    }
  }
?>
<?php
// Cập nhật mật khẩu người dùng
if(isset($_POST['update-pass'])) {
  // Kiểm tra các trường bắt buộc
  $req_fields = array('password');
  validate_fields($req_fields);
  // Nếu không có lỗi, tiếp tục xử lý
  if(empty($errors)){
    $id = (int)$e_user['id'];
    $password = remove_junk($db->escape($_POST['password']));
    $h_pass   = sha1($password);
    // Câu truy vấn để cập nhật mật khẩu người dùng
    $sql = "UPDATE users SET password='{$h_pass}' WHERE id='{$db->escape($id)}'";
    $result = $db->query($sql);
    // Nếu cập nhật thành công
    if($result && $db->affected_rows() === 1){
      $session->msg('s',"Cập nhật mật khẩu người dùng thành công.");
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    } else {
      // Nếu cập nhật không thành công
      $session->msg('d','Lỗi khi cập nhật mật khẩu người dùng!');
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    }
  } else {
    // Nếu có lỗi, hiển thị thông báo lỗi và chuyển hướng trở lại trang chỉnh sửa người dùng
    $session->msg("d", $errors);
    redirect('edit_user.php?id='.(int)$e_user['id'],false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
 <div class="row">
   <!-- Hiển thị thông báo -->
   <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
  <!-- Form cập nhật thông tin người dùng -->
  <div class="col-md-6">
     <div class="panel panel-default">
       <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Cập nhật <?php echo remove_junk(ucwords($e_user['name'])); ?> Tài khoản
        </strong>
       </div>
       <div class="panel-body">
          <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" class="clearfix">
            <div class="form-group">
                  <label for="name" class="control-label">Tên</label>
                  <input type="name" class="form-control" name="name" value="<?php echo remove_junk($e_user['name']); ?>">
            </div>
            <div class="form-group">
                  <label for="username" class="control-label">Tên đăng nhập</label>
                  <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($e_user['username'])); ?>">
            </div>
            <div class="form-group">
              <label for="level">Phân quyền người dùng</label>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group ):?>
                   <option <?php if($group['group_level'] === $e_user['user_level']) echo 'selected="selected"';?> value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
              <label for="status">Trạng thái</label>
                <select class="form-control" name="status">
                  <option <?php if($e_user['status'] === '1') echo 'selected="selected"';?>value="1">Hoạt động</option>
                  <option <?php if($e_user['status'] === '0') echo 'selected="selected"';?> value="0">Không hoạt động</option>
                </select>
            </div>
            <div class="form-group clearfix">
                    <button type="submit" name="update" class="btn btn-info">Cập nhật</button>
            </div>
        </form>
       </div>
     </div>
  </div>
  <!-- Form thay đổi mật khẩu -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Thay đổi <?php echo remove_junk(ucwords($e_user['name'])); ?> mật khẩu
        </strong>
      </div>
      <div class="panel-body">
        <form action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post" class="clearfix">
          <div class="form-group">
            <label for="password" class="control-label">Mật khẩu</label>
            <div class="input-group">
              <input type="password" class="form-control" name="password" id="password" placeholder="Nhập mật khẩu mới của bạn" required>
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" onclick="togglePassword()">👁</button>
              </span>
            </div>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update-pass" class="btn btn-danger pull-right">Thay đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
 </div>
<script>
function togglePassword() {
  var input = document.getElementById("password");
  input.type = (input.type === "password") ? "text" : "password";
}
</script>

<?php include_once('layouts/footer.php'); ?>

