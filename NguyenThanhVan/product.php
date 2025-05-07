<?php
  $page_title = 'Tất cả sản phẩm';
  require_once('includes/load.php');
  page_require_level(4);

  $products = find_by_sql("SELECT p.*, 
                                  IFNULL(c.name, 'Không có danh mục') AS categorie, 
                                  m.file_name AS image, 
                                  p.media_id
                           FROM products p
                           LEFT JOIN categories c ON p.categorie_id = c.id
                           LEFT JOIN media m ON p.media_id = m.id
                           ORDER BY categorie COLLATE utf8_vietnamese_ci ASC, p.name ASC");
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-left">
          <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm...">
        </div>
        <div class="pull-right">
          <a href="add_product.php" class="btn btn-primary">Thêm mới</a>
        </div>
      </div>

      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Hình ảnh</th>
              <th>Tiêu đề sản phẩm</th>
              <th class="text-center" style="width: 10%;">Danh mục</th>
              <th class="text-center" style="width: 10%;">Trong kho</th>
              <th class="text-center" style="width: 10%;">Ngày thêm</th>
              <th class="text-center" style="width: 100px;">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $current_category = '';
              foreach ($products as $product):
                $category = remove_junk($product['categorie']);
                if ($category != $current_category):
                  $current_category = $category;
            ?>
              <tr style="background-color: #f2f2f2;">
                <td colspan="7"><strong><?php echo $current_category; ?></strong></td>
              </tr>
            <?php endif; ?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td>
                  <?php if($product['media_id'] === '0' || empty($product['image'])): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="Không có hình ảnh">
                  <?php else: ?>
                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                  <?php endif; ?>
                </td>
                <td><?php echo remove_junk($product['name']); ?></td>
                <td class="text-center categorie-cell"><?php echo $category; ?></td>
                <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                <td class="text-center"><?php echo read_date($product['date']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Chỉnh sửa" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Xóa" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('table tbody tr');

    searchInput.addEventListener('keyup', function () {
      const keyword = this.value.toLowerCase();
      let currentCategoryRow = null;
      let showCategory = false;

      rows.forEach(row => {
        if (row.cells.length === 1) {
          currentCategoryRow = row;
          showCategory = false;
          row.style.display = 'none';
        } else {
          const productName = row.cells[2].innerText.toLowerCase();
          const categoryName = row.cells[3].innerText.toLowerCase();
          if (productName.includes(keyword) || categoryName.includes(keyword)) {
            row.style.display = '';
            showCategory = true;
          } else {
            row.style.display = 'none';
          }
        }

        if (row.cells.length === 1 && showCategory) {
          row.style.display = '';
        }
      });
    });
  });
</script>

<?php include_once('layouts/footer.php'); ?>
