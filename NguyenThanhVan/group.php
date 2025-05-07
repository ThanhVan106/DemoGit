<?php
  // Thiết lập tiêu đề trang
  $page_title = 'Tất cả nhóm';
  
  // Import các file cần thiết
  require_once('includes/load.php');
  
  // Kiểm tra quyền hạn của người dùng để xem trang này
  page_require_level(1);
  
  // Lấy tất cả các nhóm người dùng
  $all_groups = find_all('user_groups');
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
        <!-- Tiêu đề -->
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Nhóm</span>
        </strong>
        <!-- Nút thêm nhóm mới -->
        <a href="add_group.php" class="btn btn-info pull-right btn-sm"> Thêm Nhóm Mới</a>
      </div>
      
      <div class="panel-body">
        <!-- Bảng hiển thị danh sách nhóm -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <!-- Cột STT -->
              <th class="text-center" style="width: 50px;">#</th>
              <!-- Cột Tên Nhóm -->
              <th>Tên Nhóm</th>
              <!-- Cột Cấp Bậc Nhóm -->
              <th class="text-center" style="width: 20%;">Cấp Bậc Nhóm</th>
              <!-- Cột Trạng Thái -->
              <th class="text-center" style="width: 15%;">Trạng Thái</th>
              <!-- Cột Hành Động -->
              <th class="text-center" style="width: 100px;">Hành Động</th>
            </tr>
          </thead>
          
          <tbody>
            <?php foreach($all_groups as $a_group): ?>
              <tr>
                <!-- STT -->
                <td class="text-center"><?php echo count_id();?></td>
                <!-- Tên Nhóm -->
                <td><?php echo remove_junk(ucwords($a_group['group_name']))?></td>
                <!-- Cấp Bậc Nhóm -->
                <td class="text-center"><?php echo remove_junk(ucwords($a_group['group_level']))?></td>
                <!-- Trạng Thái -->
                <td class="text-center">
                  <?php if($a_group['group_status'] === '1'): ?>
                    <span class="label label-success"><?php echo "Hoạt động"; ?></span>
                  <?php else: ?>
                    <span class="label label-danger"><?php echo "Không hoạt động"; ?></span>
                  <?php endif;?>
                </td>
                <!-- Hành Động -->
                <td class="text-center">
                  <div class="btn-group">
                    <!-- Nút chỉnh sửa -->
                    <a href="edit_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Chỉnh sửa">
                      <i class="glyphicon glyphicon-pencil"></i>
                    </a>
                    <!-- Nút xóa -->
                    <a href="delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Xóa">
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
