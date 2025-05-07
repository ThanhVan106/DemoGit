<?php
  // Đặt tiêu đề trang
  $page_title = 'Doanh số hàng tháng';
  
  // Import các file cần thiết
  require_once('includes/load.php');
  
  // Kiểm tra quyền truy cập của người dùng để xem trang này
  page_require_level(3);
?>

<?php
  // Lấy năm hiện tại
  $year = date('Y');
  
  // Lấy doanh số hàng tháng cho năm hiện tại
  $sales = monthlySales($year);
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Doanh số hàng tháng</span>
        </strong>
      </div>
      <div class="panel-body">
        <!-- Bảng hiển thị doanh số hàng tháng -->
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Tên sản phẩm </th>
              <th class="text-center" style="width: 15%;"> Số lượng bán được</th>
              <th class="text-center" style="width: 15%;"> Tổng cộng </th>
              <th class="text-center" style="width: 15%;"> Ngày </th>
           </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale):?>
            <tr>
              <!-- Số thứ tự -->
              <td class="text-center"><?php echo count_id();?></td>
              <!-- Tên sản phẩm -->
              <td><?php echo remove_junk($sale['name']); ?></td>
              <!-- Số lượng bán được -->
              <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
              <!-- Tổng cộng -->
              <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
              <!-- Ngày -->
              <td class="text-center"><?php echo $sale['date']; ?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
