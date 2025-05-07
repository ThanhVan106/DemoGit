<?php
  $page_title = 'Thêm nhóm';  // Tiêu đề trang
  require_once('includes/load.php');  // Tải các tập tin cần thiết
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(1);  // Yêu cầu quyền truy cập cấp độ 1
?>

<?php
  if(isset($_POST['add'])){  // Kiểm tra nếu người dùng gửi biểu mẫu 'add'

    $req_fields = array('group-name','group-level');  // Các trường yêu cầu
    validate_fields($req_fields);  // Xác thực các trường yêu cầu

    // Kiểm tra nếu tên nhóm đã tồn tại trong cơ sở dữ liệu
    if(find_by_groupName($_POST['group-name']) === false ){
      $session->msg('d','<b>Xin lỗi!</b> Tên nhóm đã tồn tại trong cơ sở dữ liệu!');
      redirect('add_group.php', false);  // Chuyển hướng nếu tên nhóm đã tồn tại
    }elseif(find_by_groupLevel($_POST['group-level']) === false) {
      // Kiểm tra nếu cấp độ nhóm đã tồn tại trong cơ sở dữ liệu
      $session->msg('d','<b>Xin lỗi!</b> Cấp độ nhóm đã tồn tại trong cơ sở dữ liệu!');
      redirect('add_group.php', false);  // Chuyển hướng nếu cấp độ nhóm đã tồn tại
    }
    if(empty($errors)){  // Nếu không có lỗi
      $name = remove_junk($db->escape($_POST['group-name']));  // Loại bỏ ký tự đặc biệt khỏi tên nhóm
      $level = remove_junk($db->escape($_POST['group-level']));  // Loại bỏ ký tự đặc biệt khỏi cấp độ nhóm
      $status = remove_junk($db->escape($_POST['status']));  // Loại bỏ ký tự đặc biệt khỏi trạng thái

      $query  = "INSERT INTO user_groups (";  // Bắt đầu câu lệnh SQL để chèn dữ liệu vào bảng user_groups
      $query .="group_name,group_level,group_status";  // Các cột trong bảng user_groups
      $query .=") VALUES (";  // Bắt đầu các giá trị
      $query .=" '{$name}', '{$level}','{$status}'";  // Các giá trị sẽ được chèn vào
      $query .=")";  // Kết thúc câu lệnh SQL
      if($db->query($query)){  // Thực thi câu lệnh SQL
        // Thành công
        $session->msg('s',"Nhóm đã được tạo! ");  // Thông báo thành công
        redirect('add_group.php', false);  // Chuyển hướng đến trang add_group.php
      } else {
        // Thất bại
        $session->msg('d',' Xin lỗi, tạo nhóm thất bại!');  // Thông báo thất bại
        redirect('add_group.php', false);  // Chuyển hướng đến trang add_group.php
      }
    } else {
      $session->msg("d", $errors);  // Thông báo lỗi
      redirect('add_group.php',false);  // Chuyển hướng đến trang add_group.php
    }
  }
?>

<?php include_once('layouts/header.php'); ?>  <!-- Bao gồm tệp tin header -->
<div class="login-page">
  <div class="text-center">
    <h3>Thêm nhóm người dùng mới</h3>  <!-- Tiêu đề -->
  </div>
  <?php echo display_msg($msg); ?>  <!-- Hiển thị thông báo -->
  <form method="post" action="add_group.php" class="clearfix">
    <div class="form-group">
      <label for="name" class="control-label">Tên nhóm</label>
      <input type="name" class="form-control" name="group-name">  <!-- Trường nhập tên nhóm -->
    </div>
    <div class="form-group">
      <label for="level" class="control-label">Cấp độ nhóm</label>
      <input type="number" class="form-control" name="group-level">  <!-- Trường nhập cấp độ nhóm -->
    </div>
    <div class="form-group">
      <label for="status">Trạng thái</label>
      <select class="form-control" name="status">  <!-- Chọn trạng thái -->
        <option value="1">Hoạt động</option>  <!-- Tuỳ chọn hoạt động -->
        <option value="0">Ngưng hoạt động</option>  <!-- Tuỳ chọn ngưng hoạt động -->
      </select>
    </div>
    <div class="form-group clearfix">
      <button type="submit" name="add" class="btn btn-info">Cập nhật</button>  <!-- Nút gửi -->
    </div>
  </form>
</div>

<?php include_once('layouts/footer.php'); ?>  <!-- Bao gồm tệp tin footer -->
