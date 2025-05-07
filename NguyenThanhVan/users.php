<?php
$page_title = 'Tất cả người dùng';
require_once('includes/load.php');
?>
<?php
// Kiểm tra quyền truy cập của người dùng để xem trang này
page_require_level(1);
// Lấy tất cả người dùng từ cơ sở dữ liệu
$all_users = find_all_user();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <!-- Hiển thị thông báo -->
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Người dùng</span>
       </strong>
         <!-- Nút thêm người dùng mới -->
         <a href="add_user.php" class="btn btn-info pull-right">Thêm người dùng mới</a>
      </div>
     <div class="panel-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th>Tên </th>
            <th>Tên đăng nhập</th>
            <th class="text-center" style="width: 15%;">Vai trò người dùng</th>
            <th class="text-center" style="width: 10%;">Trạng thái</th>
            <th style="width: 20%;">Đăng nhập lần cuối</th>
            <th class="text-center" style="width: 100px;">Hành động</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <!-- Số thứ tự -->
           <td class="text-center"><?php echo count_id();?></td>
           <!-- Tên -->
           <td><?php echo remove_junk(ucwords($a_user['name']))?></td>
           <!-- Tên đăng nhập -->
           <td><?php echo remove_junk(ucwords($a_user['username']))?></td>
           <!-- Vai trò người dùng -->
           <td class="text-center"><?php echo remove_junk(ucwords($a_user['group_name']))?></td>
           <td class="text-center">
           <!-- Trạng thái -->
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Hoạt động"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Ngưng hoạt động"; ?></span>
          <?php endif;?>
           </td>
           <!-- Đăng nhập lần cuối -->
           <td><?php echo read_date($a_user['last_login'])?></td>
           <td class="text-center">
             <div class="btn-group">
                <!-- Nút chỉnh sửa -->
                <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Chỉnh sửa">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <!-- Nút xóa -->
                <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Xóa">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
                </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>
  <?php include_once('layouts/footer.php'); ?>
