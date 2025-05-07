<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username', 'password');
validate_fields($req_fields); // Kiểm tra các trường bắt buộc
$username = remove_junk($_POST['username']); // Xóa các ký tự không mong muốn từ tên người dùng
$password = remove_junk($_POST['password']); // Xóa các ký tự không mong muốn từ mật khẩu

if (empty($errors)) {

    $user = authenticate_v2($username, $password); // Xác thực người dùng

    if ($user) {
        // Tạo phiên làm việc với id
        $session->login($user['id']);
        // Cập nhật thời gian đăng nhập cuối cùng
        updateLastLogIn($user['id']);
        // Chuyển hướng người dùng đến trang chủ của nhóm dựa trên cấp độ người dùng
        if ($user['user_level'] === '1') {
            $session->msg("s", "Xin chào ".$user['username'].", Chào mừng đến với OSWA-INV.");
            redirect('admin.php', false);
        } elseif ($user['user_level'] === '2') {
            $session->msg("s", "Xin chào ".$user['username'].", Chào mừng đến với OSWA-INV.");
            redirect('special.php', false);
        } else {
            $session->msg("s", "Xin chào ".$user['username'].", Chào mừng đến với OSWA-INV.");
            redirect('home.php', false);
        }
    } else {
        $session->msg("d", "Xin lỗi, Tên người dùng/Mật khẩu không chính xác."); // Thông báo lỗi khi tên người dùng/mật khẩu không chính xác
        redirect('index.php', false);
    }
} else {
    $session->msg("d", $errors); // Thông báo lỗi nếu có lỗi xảy ra trong quá trình xác thực
    redirect('login_v2.php', false);
}
?>
