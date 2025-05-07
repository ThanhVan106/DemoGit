<?php
  $page_title = 'Doanh số hàng ngày';
  require_once('includes/load.php');
  page_require_level(3);

  $year   = date('Y');
  $month = date('m');

  // Hàm để lấy dữ liệu sản phẩm đã bán trong ngày (hoặc tháng) từ bảng đơn hàng
  function getDailySoldProductsFromOrders($year, $month) {
    global $db;
    $current_date_start = date('Y-m-d 00:00:00', strtotime("$year-$month-01"));
    $current_date_end = date('Y-m-d 23:59:59', strtotime("last day of $year-$month"));

    $sql = "SELECT
              oi.product_id,
              p.name AS product_name,
              SUM(oi.qty) AS total_quantity,
              SUM(oi.price * oi.qty) AS total_amount,
              DATE(o.order_date) AS sale_date
            FROM
              order_items oi
            JOIN
              orders o ON oi.order_id = o.id
            JOIN
              products p ON oi.product_id = p.id
            WHERE
              DATE(o.order_date) BETWEEN '{$current_date_start}' AND '{$current_date_end}'
            GROUP BY
              oi.product_id, DATE(o.order_date)
            ORDER BY
              DATE(o.order_date) DESC, p.name ASC";

    return $db->query($sql);
  }

  $daily_products = getDailySoldProductsFromOrders($year, $month);
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
            <span>Doanh số hàng ngày (từ Đơn hàng)</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Tên sản phẩm </th>
                <th class="text-center" style="width: 15%;"> Số lượng bán được</th>
                <th class="text-center" style="width: 15%;"> Tổng </th>
                <th class="text-center" style="width: 15%;"> Ngày </th>
              </tr>
            </thead>
          <tbody>
            <?php if($daily_products): ?>
              <?php foreach ($daily_products as $product):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk($product['product_name']); ?></td>
                <td class="text-center"><?php echo (int)$product['total_quantity']; ?></td>
                <td class="text-center"><?php echo format_money($product['total_amount']); ?></td>
                <td class="text-center"><?php echo $product['sale_date']; ?></td>
              </tr>
              <?php endforeach;?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Không có sản phẩm nào được đặt hàng trong tháng này.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>