<?php
  // Đặt tiêu đề trang
  $page_title = 'Doanh số hàng tháng';

  // Import các file cần thiết
  require_once('includes/load.php');

  // Kiểm tra quyền truy cập của người dùng để xem trang này
  page_require_level(3);

  // Lấy năm hiện tại
  $year = date('Y');

  // Lấy doanh số hàng tháng cho năm hiện tại
  $monthly_sales = monthlySales($year);
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
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Tên sản phẩm </th>
              <th class="text-center" style="width: 15%;"> Số lượng bán được</th>
              <th class="text-center" style="width: 15%;"> Tổng cộng </th>
              <th class="text-center" style="width: 15%;"> Tháng </th>
            </tr>
          </thead>
          <tbody>
            <?php if ($monthly_sales): ?>
              <?php foreach ($monthly_sales as $sale):?>
                <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td><?php echo remove_junk($sale['name']); ?></td>
                  <td class="text-center">
                    <?php if (isset($sale['total_quantity'])) {
                      echo (int)$sale['total_quantity'];
                    } else {
                      echo 'N/A'; // Hoặc một giá trị mặc định khác
                      echo "<br><small>Lỗi: Thiếu khóa 'total_quantity'</small>";
                    } ?>
                  </td>
                  <td class="text-center"><?php echo format_money($sale['total_saleing_price']); ?></td>
                  <td class="text-center">
                    <?php if (isset($sale['sale_month'])) {
                      echo date('m-Y', strtotime($sale['sale_month']));
                    } else {
                      echo 'N/A'; // Hoặc một giá trị mặc định khác
                      echo "<br><small>Lỗi: Thiếu khóa 'sale_month'</small>";
                    } ?>
                  </td>
                </tr>
              <?php endforeach;?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Không có doanh số bán hàng trong năm <?php echo $year; ?>.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<?php
// Định nghĩa hàm format_money nếu nó chưa tồn tại (để tránh lỗi)
if (!function_exists('format_money')) {
  function format_money($number, $decimals = 0, $dec_point = '.', $thousands_sep = ',') {
    return number_format($number, $decimals, $dec_point, $thousands_sep);
  }
}
?>