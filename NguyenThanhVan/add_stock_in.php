<?php
  $page_title = 'Nhập hàng';
  require_once('includes/load.php');
  page_require_level(4);
  $all_products = find_all('products');

  $conn = mysqli_connect("localhost", "root", "", "sales_system2");
  $success_message = '';
  $error_message = '';

  if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['action']) && $_GET['action'] === 'get_latest_price' && isset($_GET['product_id'])) {
  $product_id = intval($_GET['product_id']);
  $sql = "SELECT gia_nhap FROM gia_nhap 
          WHERE product_id = $product_id 
          ORDER BY ngay_nhap DESC 
          LIMIT 1";
  $result = mysqli_query($conn, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
    echo $row['gia_nhap'];
  } else {
    echo 0;
  }
  exit; // Kết thúc luôn nếu là request Ajax
}

    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $supplier_name = mysqli_real_escape_string($conn, $_POST["supplier_name"] ?? '');
    $total_amount = floatval($_POST["total_amount"]);
    $notes = mysqli_real_escape_string($conn, $_POST["notes"] ?? '');
    $product_ids = $_POST["product_id"];
    $quantities = $_POST["quantity"];
    $unit_prices = $_POST["unit_price"];

    mysqli_begin_transaction($conn);
    try {
      // Lưu phiếu nhập
      $sql_receipts = "INSERT INTO receipts (receipt_date, supplier_name, total_amount, created_at, notes)
                          VALUES ('$date', '$supplier_name', $total_amount, NOW(), '$notes')";
      if (!mysqli_query($conn, $sql_receipts)) {
        throw new Exception("Lỗi khi lưu phiếu nhập: " . mysqli_error($conn));
      }

      $receipt_id = mysqli_insert_id($conn);
      $receipt_code = 'PN-' . date('Ymd') . '-' . sprintf('%04d', $receipt_id);
      $sql_update_code = "UPDATE receipts SET code = '$receipt_code' WHERE id = $receipt_id";
      if (!mysqli_query($conn, $sql_update_code)) {
        throw new Exception("Lỗi cập nhật mã phiếu: " . mysqli_error($conn));
      }

      // Lưu vào bảng gia_nhap và bảng receipt_items
      for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = intval($product_ids[$i]);
        $quantity = intval($quantities[$i]);
        $unit_price = floatval($unit_prices[$i]);
        $total_price = round($quantity * $unit_price, 2);

        // Lưu vào bảng gia_nhap (sử dụng trường ngay_ap_dung)
$sql_insert_price = "INSERT INTO gia_nhap (product_id, gia_nhap, ngay_nhap) 
VALUES ($product_id, $unit_price, NOW())";
if (!mysqli_query($conn, $sql_insert_price)) {
throw new Exception("Lỗi lưu giá nhập: " . mysqli_error($conn));
}

        if (!mysqli_query($conn, $sql_insert_price)) {
          throw new Exception("Lỗi lưu giá nhập: " . mysqli_error($conn));
        }

        // Lưu vào bảng receipt_items
        $sql_items = "INSERT INTO receipt_items (receipt_id, product_id, quantity, unit_price, total_price)
                      VALUES ($receipt_id, $product_id, $quantity, $unit_price, $total_price)";
        if (!mysqli_query($conn, $sql_items)) {
          throw new Exception("Lỗi lưu chi tiết sản phẩm: " . mysqli_error($conn));
        }

        // Cập nhật kho
        $sql_stock = "UPDATE products
                      SET quantity = quantity + $quantity
                      WHERE id = $product_id";
        if (!mysqli_query($conn, $sql_stock)) {
          throw new Exception("Lỗi cập nhật kho: " . mysqli_error($conn));
        }
      }

      mysqli_commit($conn);
      $success_message = "Đã lưu phiếu nhập thành công với mã: $receipt_code";
    } catch (Exception $e) {
      mysqli_rollback($conn);
      $error_message = "Lỗi: " . $e->getMessage();
    }
  }
if (isset($_GET['action']) && $_GET['action'] === 'get_latest_price' && isset($_GET['product_id'])) {
  $product_id = intval($_GET['product_id']);
  $sql = "SELECT gia_nhap FROM gia_nhap 
          WHERE product_id = $product_id 
          ORDER BY ngay_nhap DESC 
          LIMIT 1";
  $result = mysqli_query($conn, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
    echo $row['gia_nhap'];
  } else {
    echo 0;
  }
  exit; // Kết thúc luôn nếu là request Ajax
}

  // Lấy danh sách sản phẩm
  $sql_products = "SELECT id, name FROM products";
  $result_products = mysqli_query($conn, $sql_products);
  $products = [];
  while ($row = mysqli_fetch_assoc($result_products)) {
    $products[] = $row;
  }
  mysqli_close($conn);
?>

<?php include_once('layouts/header.php'); ?>
<div class="modal fade" id="addReceiptModal" tabindex="-1" role="dialog" aria-labelledby="addReceiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addReceiptModalLabel">Nhập hàng</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="date">Ngày nhập:</label>
              <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label for="supplier_name">Nhà cung cấp:</label>
              <input type="text" class="form-control" name="supplier_name">
            </div>
          </div>

          <h4 class="mt-3">Chi tiết sản phẩm nhập</h4>
          <div id="product-details-container">
            <div class="product-row row">
              <div class="form-group col-md-4">
                <label>Sản phẩm:</label>
                <select class="form-control product-select" name="product_id[]" required>
                  <option value="">-- Chọn sản phẩm --</option>
                  <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id']; ?>"><?= $product['name']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label>Số lượng:</label>
                <input type="number" class="form-control quantity-input" name="quantity[]" value="1" min="1" required>
              </div>
              <div class="form-group col-md-3">
                <label>Đơn giá nhập:</label>
                <input type="number" class="form-control unit-price-input" name="unit_price[]" value="0" step="0.01" required>
              </div>
              <div class="form-group col-md-2">
                <label>Thành tiền:</label>
                <input type="text" class="form-control subtotal-input" value="0" readonly>
              </div>
              <div class="form-group col-md-1 text-center">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm remove-product-row form-control">Xóa</button>
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-info mt-2" id="add-product-row">Thêm sản phẩm</button>

          <div class="form-group mt-3">
            <label for="total_amount">Tổng số tiền:</label>
            <input type="number" class="form-control" id="total_amount" name="total_amount" value="0" readonly>
          </div>

          <div class="form-group">
            <label for="notes">Ghi chú:</label>
            <textarea class="form-control" name="notes" rows="3"></textarea>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary">Lưu phiếu nhập</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <?php if ($success_message): ?>
      <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
      <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
  $('#addReceiptModal').modal('show');

  $('#product-details-container').on('input', '.quantity-input, .unit-price-input', function () {
    const row = $(this).closest('.product-row');
    calculateSubtotal(row);
  });

  $('#add-product-row').click(function () {
    const newRow = $('.product-row:first').clone(true);
    newRow.find('select').val('');
    newRow.find('.quantity-input').val(1);
    newRow.find('.unit-price-input').val(0);
    newRow.find('.subtotal-input').val(0);
    $('#product-details-container').append(newRow);
  });

  $('#product-details-container').on('click', '.remove-product-row', function () {
    $(this).closest('.product-row').remove();
    calculateTotal();
  });

  function calculateSubtotal(row) {
    const qty = parseFloat(row.find('.quantity-input').val()) || 0;
    const price = parseFloat(row.find('.unit-price-input').val()) || 0;
    row.find('.subtotal-input').val((qty * price).toFixed(2));
    calculateTotal();
  }

  function calculateTotal() {
    let total = 0;
    $('.subtotal-input').each(function () {
      total += parseFloat($(this).val()) || 0;
    });
    $('#total_amount').val(total.toFixed(2));
  }
});
</script>

<?php include_once('layouts/footer.php'); ?>
