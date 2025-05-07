<?php
  // Đặt tiêu đề trang
  $page_title = 'Chỉnh sửa nhóm';
  
  // Đưa vào tệp load.php để tải các tài nguyên cần thiết
  require_once('includes/load.php');
  
  // Kiểm tra quyền truy cập của người dùng, chỉ cho phép người dùng cấp 1 truy cập
  page_require_level(1);
?>
<?php
  // Tìm kiếm thông tin của nhóm dựa trên ID được chọn để chỉnh sửa
  $e_group = find_by_id('user_groups',(int)$_GET['id']);
  
  // Nếu không tìm thấy nhóm, thông báo lỗi và chuyển hướng trở lại trang nhóm
  if(!$e_group){
    $session->msg("d","Thiếu id nhóm.");
    redirect('group.php');
  }
?>
<?php
  // Xử lý khi người dùng gửi biểu mẫu cập nhật nhóm
  if(isset($_POST['update'])){
    // Kiểm tra các trường bắt buộc
    $req_fields = array('group-name','group-level');
    validate_fields($req_fields);
    
    // Nếu không có lỗi
    if(empty($errors)){
      // Lấy thông tin từ biểu mẫu và loại bỏ ký tự không mong muốn
      $name = remove_junk($db->escape($_POST['group-name']));
      $level = remove_junk($db->escape($_POST['group-level']));
      $status = remove_junk($db->escape($_POST['status']));

      // Tạo câu truy vấn SQL để cập nhật thông tin nhóm vào cơ sở dữ liệu
      $query  = "UPDATE user_groups SET ";
      $query .= "group_name='{$name}',group_level='{$level}',group_status='{$status}' ";
      $query .= "WHERE ID='{$db->escape($e_group['id'])}'";
      $result = $db->query($query);
      
      // Nếu cập nhật thành công, thông báo thành công và chuyển hướng trở lại trang chỉnh sửa nhóm
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Nhóm đã được cập nhật! ");
        redirect('edit_group.php?id='.(int)$e_group['id'], false);
      } else {
        // Nếu cập nhật không thành công, thông báo lỗi và chuyển hướng trở lại trang chỉnh sửa nhóm
        $session->msg('d',' Xin lỗi! Cập nhật nhóm thất bại!');
        redirect('edit_group.php?id='.(int)$e_group['id'], false);
      }
    } else {
      // Nếu có lỗi, hiển thị thông báo lỗi và chuyển hướng trở lại trang chỉnh sửa nhóm
      $session->msg("d", $errors);
      redirect('edit_group.php?id='.(int)$e_group['id'], false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Chỉnh sửa nhóm</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id'];?>" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">Tên nhóm</label>
              <input type="name" class="form-control" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>">
        </div>
        <div class="form-group">
              <label for="level" class="control-label">Cấp độ nhóm</label>
              <input type="number" class="form-control" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
        </div>
        <div class="form-group">
          <label for="status">Trạng thái</label>
              <select class="form-control" name="status">
                <option <?php if($e_group['group_status'] === '1') echo 'selected="selected"';?> value="1"> Hoạt động </option>
                <option <?php if($e_group['group_status'] === '0') echo 'selected="selected"';?> value="0">Không hoạt động</option>
              </select>
        </div>
        <div class="form-group clearfix">
                <button type="submit" name="update" class="btn btn-info">Cập nhật</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
