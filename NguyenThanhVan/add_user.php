<?php
  $page_title = 'Thêm Người dùng';
  require_once('includes/load.php');
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(1); // Yêu cầu quyền truy cập cấp độ 1
  $groups = find_all('user_groups'); // Lấy danh sách tất cả nhóm người dùng
?>
<?php
  if(isset($_POST['add_user'])){ // Kiểm tra nếu người dùng gửi biểu mẫu 'add_user'

   $req_fields = array('full-name','username','password','level' ); // Các trường yêu cầu
   validate_fields($req_fields); // Xác thực các trường yêu cầu

   if(empty($errors)){ // Nếu không có lỗi
           $name   = remove_junk($db->escape($_POST['full-name'])); // Lấy tên và loại bỏ ký tự đặc biệt
       $username   = remove_junk($db->escape($_POST['username'])); // Lấy tên người dùng và loại bỏ ký tự đặc biệt
       $password   = remove_junk($db->escape($_POST['password'])); // Lấy mật khẩu và loại bỏ ký tự đặc biệt
       $user_level = (int)$db->escape($_POST['level']); // Lấy cấp độ người dùng và chuyển đổi thành số nguyên
       $password = sha1($password); // Mã hóa mật khẩu

        $query = "INSERT INTO users ("; // Câu lệnh SQL để chèn dữ liệu vào bảng users
        $query .="name,username,password,user_level,status"; // Các cột trong bảng users
        $query .=") VALUES ("; // Bắt đầu các giá trị
        $query .=" '{$name}', '{$username}', '{$password}', '{$user_level}','1'"; // Các giá trị sẽ được chèn vào
        $query .=")";
        if($db->query($query)){ // Thực thi câu lệnh SQL
          // Thành công
          $session->msg('s',"Tài khoản người dùng đã được tạo! "); // Thông báo thành công
          redirect('add_user.php', false); // Chuyển hướng đến trang add_user.php
        } else {
          // Thất bại
          $session->msg('d',' Xin lỗi, tạo tài khoản thất bại!'); // Thông báo thất bại
          redirect('add_user.php', false); // Chuyển hướng đến trang add_user.php
        }
   } else {
     $session->msg("d", $errors); // Thông báo lỗi
      redirect('add_user.php',false); // Chuyển hướng đến trang add_user.php
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?> <!-- Hiển thị thông báo -->
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Thêm Người dùng Mới</span> <!-- Tiêu đề -->
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="add_user.php">
            <div class="form-group">
                <label for="name">Tên</label> <!-- Nhãn trường nhập tên -->
                <input type="text" class="form-control" name="full-name" placeholder="Tên đầy đủ"> <!-- Ô nhập tên -->
            </div>
            <div class="form-group">
                <label for="username">Tên người dùng</label> <!-- Nhãn trường nhập tên người dùng -->
                <input type="text" class="form-control" name="username" placeholder="Tên người dùng"> <!-- Ô nhập tên người dùng -->
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label> <!-- Nhãn trường nhập mật khẩu -->
                <input type="password" class="form-control" name ="password"  placeholder="Mật khẩu"> <!-- Ô nhập mật khẩu -->
            </div>
            <div class="form-group">
              <label for="level">Vai trò người dùng</label> <!-- Nhãn chọn cấp độ người dùng -->
                <select class="form-control" name="level"> <!-- Dropdown chọn cấp độ người dùng -->
                  <?php foreach ($groups as $group ):?> <!-- Vòng lặp để lấy danh sách các nhóm -->
                   <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option> <!-- Tuỳ chọn cấp độ và tên nhóm -->
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-primary">Thêm Người dùng</button> <!-- Nút thêm người dùng -->
            </div>
        </form>
        </div>

      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?> <!-- Bao gồm tệp footer -->
