<?php
  // Đặt tiêu đề trang
  $page_title = 'Chỉnh sửa danh mục';
  
  // Đưa vào tệp load.php để tải các tài nguyên cần thiết
  require_once('includes/load.php');
  
  // Kiểm tra quyền truy cập của người dùng
  page_require_level(4);
?>
<?php
  // Tìm kiếm thông tin của danh mục dựa trên ID được chọn để chỉnh sửa
  $categorie = find_by_id('categories',(int)$_GET['id']);
  
  // Nếu không tìm thấy danh mục, thông báo lỗi và chuyển hướng trở lại trang danh mục
  if(!$categorie){
    $session->msg("d","Thiếu id danh mục.");
    redirect('categorie.php');
  }
?>

<?php
// Xử lý khi người dùng gửi biểu mẫu chỉnh sửa danh mục
if(isset($_POST['edit_cat'])){
  // Kiểm tra các trường bắt buộc
  $req_field = array('categorie-name');
  validate_fields($req_field);
  
  // Lấy tên danh mục từ biểu mẫu và loại bỏ ký tự không mong muốn
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  
  // Nếu không có lỗi
  if(empty($errors)){
    // Thực hiện cập nhật tên danh mục vào cơ sở dữ liệu
    $sql = "UPDATE categories SET name='{$cat_name}'";
    $sql .= " WHERE id='{$categorie['id']}'";
    $result = $db->query($sql);
    
    // Nếu cập nhật thành công, thông báo thành công và chuyển hướng trở lại trang danh mục
    if($result && $db->affected_rows() === 1) {
      $session->msg("s", "Cập nhật danh mục thành công");
      redirect('categorie.php',false);
    } else {
      // Nếu cập nhật không thành công, thông báo lỗi và chuyển hướng trở lại trang danh mục
      $session->msg("d", "Xin lỗi! Cập nhật thất bại");
      redirect('categorie.php',false);
    }
  } else {
    // Nếu có lỗi, hiển thị thông báo lỗi và chuyển hướng trở lại trang danh mục
    $session->msg("d", $errors);
    redirect('categorie.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Chỉnh sửa <?php echo remove_junk(ucfirst($categorie['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_categorie.php?id=<?php echo (int)$categorie['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Cập nhật danh mục</button>
       </form>
       </div>
     </div>
   </div>
</div>

<?php include_once('layouts/footer.php'); ?>
