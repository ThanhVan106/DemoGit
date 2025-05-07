<?php
  // Đặt tiêu đề trang
  $page_title = 'Tất cả ảnh';
  
  // Import các file cần thiết
  require_once('includes/load.php');
  
  // Kiểm tra quyền truy cập của người dùng để xem trang này
  page_require_level(2);

  // Lấy tất cả các tệp media từ cơ sở dữ liệu hoặc tìm kiếm
  $search = isset($_POST['search']) ? remove_junk($db->escape($_POST['search'])) : '';
  if ($search) {
    $media_files = find_media_by_name($search);
  } else {
    $media_files = find_all('media');
  }
?>

<?php
  // Xử lý khi người dùng tải lên ảnh
  if(isset($_POST['submit'])) {
    // Tạo một đối tượng Media mới
    $photo = new Media();
    
    // Upload file ảnh
    $photo->upload($_FILES['file_upload']);
    
    // Xử lý tệp media
    if($photo->process_media()) {
      // Thông báo khi tải lên thành công
      $session->msg('s','Ảnh đã được tải lên.');
      redirect('media.php');
    } else {
      // Thông báo khi có lỗi xảy ra trong quá trình xử lý ảnh
      $session->msg('d',join($photo->errors));
      redirect('media.php');
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>Tất cả ảnh</span>
        <div class="pull-right">
          <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <div class="input-group">
                <!-- Nút chọn file ảnh -->
                <span class="input-group-btn">
                  <input type="file" name="file_upload" id="file_upload" class="btn btn-primary btn-file" onchange="updateButtonText()"/>
                </span>
                <!-- Nút tải lên giữ nguyên -->
                <button type="submit" name="submit" class="btn btn-default">Tải lên</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Thanh tìm kiếm ảnh -->
      <div class="panel-body">
        <form method="POST" action="media.php" class="form-inline text-center">
          <div class="form-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm ảnh..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
          </div>
        </form>
      </div>

      <div class="panel-body">
        <!-- Bảng hiển thị danh sách ảnh đã tải lên -->
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th class="text-center">Ảnh</th>
              <th class="text-center">Tên ảnh</th>
              <th class="text-center" style="width: 20%;">Loại ảnh</th>
              <th class="text-center" style="width: 50px;">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($media_files as $media_file): ?>
            <tr class="list-inline">
              <!-- Số thứ tự của ảnh -->
              <td class="text-center"><?php echo count_id();?></td>
              <!-- Hiển thị ảnh -->
              <td class="text-center">
                <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-thumbnail" />
              </td>
              <!-- Hiển thị tên của ảnh -->
              <td class="text-center">
                <?php echo $media_file['file_name'];?>
              </td>
              <!-- Hiển thị loại của ảnh -->
              <td class="text-center">
                <?php echo $media_file['file_type'];?>
              </td>
              <!-- Button để xóa ảnh -->
              <td class="text-center">
                <a href="delete_media.php?id=<?php echo (int) $media_file['id'];?>" class="btn btn-danger btn-xs"  title="Xóa">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>


