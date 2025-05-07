<?php
  // Đặt tiêu đề trang
  $page_title = 'Chỉnh sửa sản phẩm';
  
  // Đưa vào tệp load.php để tải các tài nguyên cần thiết
  require_once('includes/load.php');
  
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(4);
?>
<?php
// Tìm sản phẩm để chỉnh sửa dựa trên ID được truyền vào
$product = find_by_id('products',(int)$_GET['id']);

// Lấy tất cả danh mục và tất cả hình ảnh từ cơ sở dữ liệu
$all_categories = find_all('categories');
$all_photo = find_all('media');

// Nếu không tìm thấy sản phẩm, thông báo lỗi và chuyển hướng trở lại trang sản phẩm
if(!$product){
  $session->msg("d","Thiếu id sản phẩm.");
  redirect('product.php');
}
?>
<?php
// Xử lý khi người dùng gửi biểu mẫu để cập nhật sản phẩm
 if(isset($_POST['product'])){
    // Kiểm tra các trường bắt buộc
    $req_fields = array('product-title','product-categorie');
    validate_fields($req_fields);

   // Nếu không có lỗi
   if(empty($errors)){
       // Lấy thông tin từ biểu mẫu và loại bỏ ký tự không mong muốn
       $p_name  = remove_junk($db->escape($_POST['product-title']));
       $p_cat   = (int)$_POST['product-categorie'];
       
       // Xử lý trường hợp không chọn ảnh
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
       
       // Tạo câu truy vấn SQL để cập nhật thông tin sản phẩm vào cơ sở dữ liệu
       $query   = "UPDATE products SET";
       $query  .=" name ='{$p_name}', categorie_id ='{$p_cat}', media_id='{$media_id}'";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
       
       // Nếu cập nhật thành công, thông báo thành công và chuyển hướng trở lại trang sản phẩm
       if($result && $db->affected_rows() === 1){
         $session->msg('s',"Product updated ");
         redirect('product.php', false);
       } else {
         // Nếu cập nhật không thành công, thông báo lỗi và chuyển hướng trở lại trang chỉnh sửa sản phẩm
         $session->msg('d',' Rất tiếc không cập nhật được!');
         redirect('edit_product.php?id='.$product['id'], false);
       }

   } else{
       // Nếu có lỗi, hiển thị thông báo lỗi và chuyển hướng trở lại trang chỉnh sửa sản phẩm
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
   }

 }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Chỉnh sửa sản phẩm</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']);?>">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                    <option value="">Chọn danh mục</option>
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Không hình ảnh</option>
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <button type="submit" name="product" class="btn btn-danger">Cập nhật</button>
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
