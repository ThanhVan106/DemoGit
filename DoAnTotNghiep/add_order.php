<?php
  $page_title = 'Thêm đơn hàng';
  require_once('includes/load.php');
  page_require_level(3);
  $products = find_all('products');
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>

    <!-- Modal Thêm đơn hàng -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" role="dialog" aria-labelledby="addOrderLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form method="post" action="save_order.php">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="addOrderLabel">THÊM ĐƠN HÀNG</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
              <div class="form-group">
                <label>Tên khách hàng:</label>
                <input type="text" name="customer_name" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Ngày tạo:</label>
                <input type="date" name="order_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
              </div>

              <h5>Danh sách sản phẩm</h5>
              <table class="table table-bordered" id="product-table">
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="order-items">
                  <tr>
                    <td>
                      <select name="product_id[]" class="form-control" onchange="updatePrice(this)">
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php foreach ($products as $product): ?>
                          <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['sale_price']; ?>">
                            <?php echo $product['name']; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td><input type="number" name="quantity[]" class="form-control" min="1" value="1" onchange="updateTotal(this)"></td>
                    <td><input type="text" class="form-control unit-price" readonly></td>
                    <td><input type="text" class="form-control item-total" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">✖</button></td>
                  </tr>
                </tbody>
              </table>
              <button type="button" class="btn btn-secondary" onclick="addRow()">+ Thêm sản phẩm</button>

              <div class="form-group mt-3">
                <label>Tổng tiền:</label>
                <input type="text" name="total_price" id="total-price" class="form-control" readonly>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Lưu đơn hàng</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<!-- JavaScript hiển thị modal ngay khi vào trang -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('#addOrderModal').modal('show');
  });

  function updatePrice(select) {
    const row = select.closest('tr');
    const price = parseFloat(select.selectedOptions[0].dataset.price || 0);
    row.querySelector('.unit-price').value = price.toFixed(2);
    updateTotal(row.querySelector('input[name="quantity[]"]'));
  }

  function updateTotal(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(input.value || 0);
    const price = parseFloat(row.querySelector('.unit-price').value || 0);
    const total = quantity * price;
    row.querySelector('.item-total').value = total.toFixed(2);
    calculateGrandTotal();
  }

  function calculateGrandTotal() {
    let sum = 0;
    document.querySelectorAll('.item-total').forEach(e => {
      sum += parseFloat(e.value || 0);
    });
    document.getElementById('total-price').value = sum.toFixed(2);
  }

  function addRow() {
    const tbody = document.getElementById('order-items');
    const firstRow = tbody.querySelector('tr');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input, select').forEach(el => {
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
      else el.value = (el.type === 'number') ? 1 : '';
    });
    tbody.appendChild(newRow);
  }

  function removeRow(btn) {
    const row = btn.closest('tr');
    const tbody = document.getElementById('order-items');
    if (tbody.rows.length > 1) {
      row.remove();
      calculateGrandTotal();
    }
  }
</script>

<?php include_once('layouts/footer.php'); ?>
