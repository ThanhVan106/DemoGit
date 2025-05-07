<?php
  $page_title = 'Thêm giá bán';
  require_once('includes/load.php');
  page_require_level(3);
  $products = find_all('products');

  if (isset($_POST['add_price'])) {
    $product_id = (int)$_POST['product_id'];
    $gia = (float)$_POST['gia'];
    $ngay = $_POST['ngay_ap_dung'];
  
    // Lấy giá nhập mới nhất từ bảng gia_nhap
    $sql_gia_nhap = "SELECT gia_nhap FROM gia_nhap 
                     WHERE product_id = '{$product_id}' 
                     ORDER BY ngay_nhap DESC 
                     LIMIT 1";
    $result = $db->query($sql_gia_nhap);
    $gia_nhap = 0;
  
    if ($db->num_rows($result) > 0) {
      $row = $db->fetch_assoc($result);
      $gia_nhap = (float)$row['gia_nhap'];
    }
  
    // So sánh giá bán với giá nhập
    if ($gia <= $gia_nhap) {
      $session->msg("d", "Giá bán phải cao hơn giá nhập hiện tại ({$gia_nhap}).");
    } else {
      // Thêm giá vào bảng gia_ban
      $sql = "INSERT INTO gia_ban (product_id, gia, ngay_ap_dung) 
              VALUES ('{$product_id}', '{$gia}', '{$ngay}')";
      if ($db->query($sql)) {
        $session->msg("s", "Thêm giá thành công.");
        // redirect nếu muốn quay lại trang quản lý
        redirect('manage_price.php', false);
      } else {
        $session->msg("d", "Lỗi thêm giá.");
      }
    }
  }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <form method="post">
      <div class="form-group">
        <label>Sản phẩm</label>
        <select name="product_id" class="form-control">
          <?php foreach ($products as $product): ?>
            <option value="<?= $product['id'] ?>"><?= remove_junk($product['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Giá bán</label>
        <input type="number" name="gia" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Ngày áp dụng</label>
        <input type="date" name="ngay_ap_dung" class="form-control" required>
      </div>
      <button type="submit" name="add_price" class="btn btn-primary">Thêm</button>
    </form>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
