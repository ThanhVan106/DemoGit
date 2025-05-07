<?php
  $page_title = 'Thêm Sản phẩm';  // Tiêu đề của trang
  require_once('includes/load.php');  // Tải các tệp cần thiết
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(4);  // Yêu cầu quyền truy cập cấp độ 4
  $all_categories = find_all('categories');  // Lấy tất cả các danh mục sản phẩm
  $all_photo = find_all('media');  // Lấy tất cả các ảnh
?>
<?php
 if(isset($_POST['add_product'])){  // Nếu người dùng gửi biểu mẫu 'add_product'

   $req_fields = array('product-title','product-categorie');  // Các trường yêu cầu
   validate_fields($req_fields);  // Xác thực các trường

   if(empty($errors)){  // Nếu không có lỗi
     $p_name  = remove_junk($db->escape($_POST['product-title']));  // Loại bỏ ký tự đặc biệt từ tên sản phẩm
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));  // Loại bỏ ký tự đặc biệt từ danh mục sản phẩm

     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {  // Nếu không có ảnh sản phẩm được chọn
       $media_id = '0';  // Đặt ID phương tiện là 0
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));  // Loại bỏ ký tự đặc biệt từ ID phương tiện
     }

     $date    = make_date();  // Lấy ngày hiện tại
     $query  = "INSERT INTO products (";  // Bắt đầu câu lệnh SQL để chèn dữ liệu vào bảng products
     $query .=" name,categorie_id,media_id,date";  // Các cột trong bảng products
     $query .=") VALUES (";  // Bắt đầu các giá trị
     $query .=" '{$p_name}', '{$p_cat}', '{$media_id}', '{$date}'";  // Các giá trị sẽ được chèn
     $query .=")";  // Kết thúc câu lệnh SQL

     if($db->query($query)){  // Thực thi câu lệnh SQL
       $session->msg('s',"Sản phẩm đã được thêm ");  // Thông báo thành công
       redirect('add_product.php', false);  // Chuyển hướng đến trang add_product.php
     } else {
       $session->msg('d',' Xin lỗi, thêm sản phẩm thất bại!');  // Thông báo thất bại
       redirect('product.php', false);  // Chuyển hướng đến trang product.php
     }
   } else{
     $session->msg("d", $errors);  // Thông báo lỗi
     redirect('add_product.php',false);  // Chuyển hướng đến trang add_product.php
   }
 }
?>
<?php include_once('layouts/header.php'); ?>  <!-- Bao gồm tệp header -->
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>  <!-- Hiển thị thông báo -->
  </div>
</div>
<div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Thêm Sản phẩm Mới</span>  <!-- Tiêu đề -->
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Tên sản phẩm">  <!-- Ô nhập tên sản phẩm -->
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Chọn Danh mục Sản phẩm</option>
                    <?php  foreach ($all_categories as $cat): ?>  <!-- Vòng lặp để lấy danh sách các danh mục -->
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Chọn Ảnh Sản phẩm</option>
                    <?php  foreach ($all_photo as $photo): ?>  <!-- Vòng lặp để lấy danh sách các ảnh -->
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <button type="submit" name="add_product" class="btn btn-danger">Thêm sản phẩm</button>  <!-- Nút thêm sản phẩm -->
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>  <!-- Bao gồm tệp footer -->
