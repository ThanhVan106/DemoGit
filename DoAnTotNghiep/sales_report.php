<?php
$page_title = 'Báo cáo doanh số';
require_once('includes/load.php');
// Kiểm tra quyền truy cập của người dùng để xem trang này
page_require_level(3);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <!-- Hiển thị thông báo -->
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
        <!-- Tiêu đề của panel -->
        <h3 class="panel-title">Báo cáo doanh số</h3>
      </div>
      <div class="panel-body">
          <form class="clearfix" method="post" action="sale_report_process.php">
            <div class="form-group">
              <!-- Nhập phạm vi ngày bắt đầu và kết thúc -->
              <label class="form-label">Phạm vi ngày</label>
                <div class="input-group">
                  <input type="text" class="datepicker form-control" name="start-date" placeholder="Từ ngày">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="end-date" placeholder="Đến ngày">
                </div>
            </div>
            <div class="form-group">
                 <!-- Nút để tạo báo cáo -->
                 <button type="submit" name="submit" class="btn btn-primary">Tạo báo cáo</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
