<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username', 'password');
validate_fields($req_fields); // Kiểm tra các trường bắt buộc
$username = remove_junk($_POST['username']); // Xóa các ký tự không mong muốn từ tên người dùng
$password = remove_junk($_POST['password']); // Xóa các ký tự không mong muốn từ mật khẩu

if (empty($errors)) {
    $user_id = authenticate($username, $password); // Xác thực người dùng
    if ($user_id) {
        // Tạo phiên làm việc với id
        $session->login($user_id);
        // Cập nhật thời gian đăng nhập cuối cùng
        updateLastLogIn($user_id);
        $session->msg("s", "Chào mừng đến với Hệ thống Quản lý Bán hàng"); // Thông báo chào mừng khi đăng nhập thành công
        redirect('admin.php', false);
    } else {
        $session->msg("d", "Xin lỗi, Tên người dùng/Mật khẩu không chính xác."); // Thông báo lỗi khi tên người dùng/mật khẩu không chính xác
        redirect('index.php', false);
    }
} else {
    $session->msg("d", $errors); // Thông báo lỗi nếu có lỗi xảy ra trong quá trình xác thực
    redirect('index.php', false);
}
?>
