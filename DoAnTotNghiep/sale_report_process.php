<?php
// Đặt tiêu đề của trang
$page_title = 'Báo cáo doanh số';

// Import các file cần thiết
require_once('includes/load.php');

// Kiểm tra quyền truy cập của người dùng để xem trang này
page_require_level(3);
?>

<?php
// Xử lý khi form được gửi đi
if(isset($_POST['submit'])){
  // Xác định các trường bắt buộc
  $req_dates = array('start-date','end-date');
  validate_fields($req_dates);

  // Nếu không có lỗi
  if(empty($errors)):
    // Lấy ngày bắt đầu và ngày kết thúc từ form
    $start_date   = remove_junk($db->escape($_POST['start-date']));
    $end_date     = remove_junk($db->escape($_POST['end-date']));

    // Tìm kiếm doanh số trong khoảng thời gian đã chọn
    $results      = find_sale_by_dates($start_date,$end_date);
  else:
    // Nếu có lỗi, thông báo và chuyển hướng lại trang báo cáo doanh số
    $session->msg("d", $errors);
    redirect('sales_report.php', false);
  endif;
} else {
  // Nếu không có dữ liệu được gửi đi từ form, thông báo và chuyển hướng lại trang báo cáo doanh số
  $session->msg("d", "Chọn ngày");
  redirect('sales_report.php', false);
}
?>
<!doctype html>
<html lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $page_title; ?></title>
  <!-- Import CSS Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
  <style>
    /* CSS cho trang in */
    @media print {
      html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
      }
      .page-break {
        page-break-before:always;
        width: auto;
        margin: auto;
      }
    }
    /* CSS cho trang web */
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
    .sale-head{
      margin: 40px 0;
      text-align: center;
    }
    .sale-head h1,.sale-head strong{
      padding: 10px 20px;
      display: block;
    }
    .sale-head h1{
      margin: 0;
      border-bottom: 1px solid #212121;
    }
    table thead tr th {
      text-align: center;
      border: 1px solid #ededed;
    }
    table tbody tr td{
      vertical-align: middle;
    }
    .sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
      border: 1px solid #212121;
      white-space: nowrap;
    }
    .sale-head h1,table thead tr th,table tfoot tr td{
      background-color: #f8f8f8;
    }
    tfoot{
      color:#000;
      text-transform: uppercase;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <?php if($results): ?>
    <!-- Phần hiển thị khi có kết quả tìm kiếm -->
    <div class="page-break">
      <div class="sale-head" style="margin-bottom: 0px; text-align: center;">
        <!-- Tiêu đề của trang in -->
        <h1 style="margin: 0;">Hệ thống quản lý bán hàng - Báo cáo doanh số</h1>
        <!-- Hiển thị khoảng thời gian đã chọn -->
        <strong style="display: block; margin-top: 5px;">
          <?php if(isset($start_date)){ echo $start_date;}?> ĐẾN NGÀY <?php if(isset($end_date)){echo $end_date;}?>
        </strong>
      </div>
      <!-- Bảng hiển thị kết quả -->
      <table class="table table-border">
        <thead>
          <tr>
            <th>Ngày</th>
            <th>Tên sản phẩm</th>
            <th>Giá nhập</th>
            <th>Giá bán</th>
            <th>Số lượng</th>
            <th>TỔNG</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $result): ?>
            <tr>
              <!-- Hiển thị thông tin từng hàng trong kết quả -->
              <td class=""><?php echo remove_junk($result['date']);?></td>
              <td class="desc">
                <h6><?php echo remove_junk(ucfirst($result['name']));?></h6>
              </td>
              <td class="text-right"><?php echo remove_junk($result['buy_price']);?></td>
              <td class="text-right"><?php echo remove_junk($result['sale_price']);?></td>
              <td class="text-right"><?php echo remove_junk($result['total_sales']);?></td>
              <td class="text-right"><?php echo remove_junk($result['total_saleing_price']);?></td>
</tr>
<?php endforeach; ?>
</tbody>
<!-- Hiển thị tổng cộng và lợi nhuận -->
<tfoot>
<tr class="text-right">
<td colspan="4"></td>
<td colspan="1">Tổng cộng</td>
<!-- Hiển thị tổng số tiền từ tất cả các sản phẩm -->
<td> ₹<?php echo number_format(total_price($results)[0], 2);?></td>
</tr>
<tr class="text-right">
<td colspan="4"></td>
<td colspan="1">Lợi nhuận</td>
<!-- Hiển thị tổng lợi nhuận từ tất cả các sản phẩm -->
<td> ₹<?php echo number_format(total_price($results)[1], 2);?></td>
</tr>
</tfoot>
</table>
</div>

  <?php
    // Nếu không có kết quả nào được tìm thấy
    else:
      // Hiển thị thông báo và chuyển hướng lại trang báo cáo doanh số
      $session->msg("d", "Xin lỗi, không tìm thấy bất kỳ doanh số nào. ");
      redirect('sales_report.php', false);
  endif;
  ?>
</body>
</html>
<?php
// Đóng kết nối với cơ sở dữ liệu
if(isset($db)) { $db->db_disconnect(); }
?>
