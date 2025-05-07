<?php
  $page_title = 'Trang chủ Admin';
  require_once('includes/load.php');
  // Kiểm tra cấp độ người dùng có quyền xem trang này
   page_require_level(1); // Yêu cầu quyền cấp độ 1
   date_default_timezone_set('Asia/Ho_Chi_Minh');
   $now = date('H:i:s - d/m/Y');

?>
<?php
 $c_categorie     = count_by_id('categories'); // Đếm số lượng danh mục
 $c_product       = count_by_id('products'); // Đếm số lượng sản phẩm
 $c_sale          = count_by_id('orders'); // Đếm số lượng đơn hàng
 $c_user          = count_by_id('users'); // Đếm số lượng người dùng
 $products_sold   = find_higest_saleing_product('10'); // Tìm sản phẩm bán chạy nhất
 $recent_products = find_recent_product_added('5'); // Tìm sản phẩm mới được thêm gần đây
 $recent_sales    = find_recent_sale_added('5'); // Tìm đơn hàng mới được thêm gần đây
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?> <!-- Hiển thị thông báo -->
   </div>
</div>
<div class="row">
    <a href="users.php" style="color:black;"> <!-- Liên kết đến trang quản lý người dùng -->
		<div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-secondary1">
          <i class="glyphicon glyphicon-user"></i> <!-- Biểu tượng người dùng -->
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2> <!-- Hiển thị số lượng người dùng -->
          <p class="text-muted">Người dùng</p> <!-- Chú thích -->
        </div>
       </div>
    </div>
	</a>
	
	<a href="categorie.php" style="color:black;"> <!-- Liên kết đến trang quản lý danh mục -->
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-th-large"></i> <!-- Biểu tượng danh mục -->
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_categorie['total']; ?> </h2> <!-- Hiển thị số lượng danh mục -->
          <p class="text-muted">Danh mục</p> <!-- Chú thích -->
        </div>
       </div>
    </div>
	</a>
	
	<a href="product.php" style="color:black;"> <!-- Liên kết đến trang quản lý sản phẩm -->
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-blue2">
          <i class="glyphicon glyphicon-shopping-cart"></i> <!-- Biểu tượng giỏ hàng -->
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_product['total']; ?> </h2> <!-- Hiển thị số lượng sản phẩm -->
          <p class="text-muted">Sản phẩm</p> <!-- Chú thích -->
        </div>
       </div>
    </div>
	</a>
	
	<a href="orders.php" style="color:black;"> <!-- Liên kết đến trang quản lý bán hàng -->
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
         <i class="glyphicon glyphicon-piggy-bank"></i> <!-- Biểu tượng giỏ hàng -->
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_sale['total']; ?></h2> <!-- Hiển thị số lượng đơn hàng -->
          <p class="text-muted">Bán hàng</p> <!-- Chú thích -->
        </div>
       </div>
    </div>
	</a>
  
</div>
  
<div class="row">
   <div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong
         >
           <span class="glyphicon glyphicon-th"></span>
           <span>Sản phẩm bán chạy nhất</span> <!-- Tiêu đề -->
         </strong>
       </div>
       <div class="panel-body">
         <table class="table table-striped table-bordered table-condensed">
          <thead>
           <tr>
             <th>Tên sản phẩm</th> <!-- Tiêu đề cột -->
             <th>Số lần đã bán</th> <!-- Tiêu đề cột -->
             <th>Tổng số lượng bán</th> <!-- Tiêu đề cột -->
           <tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as $product_sold): ?> <!-- Vòng lặp hiển thị danh sách sản phẩm bán chạy -->
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td> <!-- Tên sản phẩm -->
                <td><?php echo (int)$product_sold['totalSold']; ?></td> <!-- Số lượng đã bán -->
                <td><?php echo (int)$product_sold['totalQty']; ?></td> <!-- Tổng số lượng -->
              </tr>
            <?php endforeach; ?>
          <tbody>
         </table>
       </div>
     </div>
   </div>
   <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>ĐƠN MỚI NHẤT</span> <!-- Tiêu đề -->
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-striped table-bordered table-condensed">
       <thead>
         <tr>
           <th class="text-center" style="width: 50px;">#</th> <!-- Tiêu đề cột -->
           <th>Tên Khách Hàng</th> <!-- Tiêu đề cột -->
           <th>Ngày</th> <!-- Tiêu đề cột -->
           <th>Tổng doanh thu</th> <!-- Tiêu đề cột -->
         </tr>
       </thead>
       <tbody>
         <?php foreach ($recent_sales as $recent_sale): ?> <!-- Vòng lặp hiển thị danh sách đơn hàng mới nhất -->
         <tr>
           <td class="text-center"><?php echo count_id();?></td> <!-- Số thứ tự -->
           <td>
            <a href="order_detail.php?id=<?php echo (int)$recent_sale['id']; ?>"> <!-- Liên kết đến trang chỉnh sửa đơn hàng -->
             <?php echo remove_junk(first_character($recent_sale['customer_name'])); ?> <!-- Tên sản phẩm -->
           </a>
           </td>
           <td><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td> <!-- Ngày -->
           <td><?php echo number_format($recent_sale['total_price']); ?> đ</td> <!-- Tổng doanh thu -->
        </tr>

       <?php endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sản phẩm đã thêm gần đây</span> <!-- Tiêu đề -->
        </strong>
      </div>
      <div class="panel-body">
        <div class="list-group">
          <?php foreach ($recent_products as $recent_product): ?> <!-- Vòng lặp hiển thị danh sách sản phẩm mới nhất -->
            <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo (int)$recent_product['id'];?>"> <!-- Liên kết đến trang chỉnh sửa sản phẩm -->
                <h4 class="list-group-item-heading">
                 <?php if($recent_product['media_id'] === '0'): ?> <!-- Kiểm tra nếu không có hình ảnh -->
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt=""> <!-- Hình ảnh mặc định -->
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $recent_product['image'];?>" alt="" /> <!-- Hiển thị hình ảnh sản phẩm -->
                <?php endif;?>
                <?php echo remove_junk(first_character($recent_product['name']));?> <!-- Tên sản phẩm -->
                  <span class="label label-warning pull-right">
                  ₹<?php echo (int)$recent_product['sale_price']; ?> <!-- Giá bán -->
                  </span>
                </h4>
                <span class="list-group-item-text pull-right">
                <?php echo remove_junk(first_character($recent_product['categorie'])); ?> <!-- Danh mục -->
              </span>
          </a>
      <?php endforeach; ?>
    </div>
  </div>
 </div>
</div>
 </div>
<div class="row">
</div>

<?php include_once('layouts/footer.php'); ?>
