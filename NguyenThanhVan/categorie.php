<?php
  // Thiết lập tiêu đề trang
  $page_title = 'Tất cả danh mục';

  // Đưa các hàm và biến cần thiết vào script
  require_once('includes/load.php');

  // Kiểm tra quyền truy cập của người dùng
  page_require_level(4);
  
  // Lấy tất cả danh mục từ cơ sở dữ liệu
  $all_categories = find_by_sql("SELECT * FROM categories ORDER BY name COLLATE utf8_vietnamese_ci ASC");
?>
<?php
  // Xử lý khi người dùng thêm danh mục mới
  if (isset($_POST['add_cat'])) {
    $req_field = array('categorie-name');
    validate_fields($req_field);
  
    $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  
    if (empty($errors)) {
      // Kiểm tra xem danh mục đã tồn tại chưa
      $check_sql = "SELECT id FROM categories WHERE name = '{$cat_name}'";
      $result = $db->query($check_sql);
  
      if ($result && $result->num_rows > 0) {
        $session->msg("d", "Danh mục '{$cat_name}' đã tồn tại.");
        redirect('categorie.php', false);
      } else {
        $sql  = "INSERT INTO categories (name)";
        $sql .= " VALUES ('{$cat_name}')";
        if ($db->query($sql)) {
          $session->msg("s", "Thêm danh mục mới thành công");
          redirect('categorie.php', false);
        } else {
          $session->msg("d", "Xin lỗi, không thể thêm.");
          redirect('categorie.php', false);
        }
      }
    } else {
      $session->msg("d", $errors);
      redirect('categorie.php', false);
    }
  }
  
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <!-- Hiển thị thông báo -->
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Thêm Danh mục mới</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="categorie.php">
          <div class="form-group">
            <!-- Ô nhập tên danh mục mới -->
            <input type="text" class="form-control" name="categorie-name" placeholder="Tên danh mục">
          </div>
          <!-- Nút thêm danh mục -->
          <button type="submit" name="add_cat" class="btn btn-primary">Thêm danh mục</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Tất cả các danh mục</span>
        </strong>
      </div>
      <div class="panel-body">
        <!-- Bảng hiển thị tất cả danh mục -->
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Danh mục</th>
              <th class="text-center" style="width: 100px;">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_categories as $cat):?>
              <tr>
                <!-- STT -->
                <td class="text-center"><?php echo count_id();?></td>
                <!-- Tên danh mục -->
                <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <!-- Nút chỉnh sửa danh mục -->
                    <a href="edit_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Chỉnh sửa">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <!-- Nút xóa danh mục -->
                    <a href="delete_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Xóa">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
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

               
