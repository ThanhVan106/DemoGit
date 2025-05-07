<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) {
    redirect('home.php', false);
  }
?>
<?php include_once('layouts/header.php'); ?>

<!-- Bootstrap CSS + JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal luôn hiển thị và to hơn -->
<div class="modal show d-block" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- To hơn bình thường -->
    <div class="modal-content rounded-4 shadow p-4">
      <div class="modal-header border-0">
        <h3 class="modal-title w-100 text-center fw-bold" id="loginModalLabel">Đăng Nhập</h3>
      </div>
      <div class="modal-body">
        <?php echo display_msg($msg); ?>
        <form method="post" action="auth.php">
          <div class="mb-3">
            <label for="username" class="form-label fs-5">Tên Người Dùng</label>
            <input type="text" class="form-control form-control-lg" name="username" placeholder="Tên đăng nhập" required>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label fs-5">Mật Khẩu</label>
            <input type="password" class="form-control form-control-lg" name="password" placeholder="Mật khẩu" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-danger btn-lg">Đăng Nhập</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Nền mờ -->
<style>
  body {
    background-color: rgba(0, 0, 0, 0.4);
    overflow: hidden;
  }
</style>

<?php include_once('layouts/footer.php'); ?>
