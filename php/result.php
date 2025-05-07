<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả</title>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    // Thư mục lưu trữ avatar
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($_FILES["avatar"]["name"]);

    // Kiểm tra xem file đã được tải lên thành công chưa
    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $uploadFile)) {
        echo "File avatar đã được upload thành công.<br>";
    } else {
        echo "Có lỗi xảy ra khi tải lên file.<br>";
    }

    ?>

    <table border="1" style="width: 600px; margin-top: 20px;">
        <tr>
            <td colspan="2" style="text-align: center; font-size: 24px; font-weight: bold;">Kết quả</td>
        </tr>
        <tr>
            <td>Full name</td>
            <td><?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : 'Chưa nhập'; ?></td>
        </tr>
        <tr>
            <td>User name</td>
            <td><?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'Chưa nhập'; ?></td>
        </tr>
        <tr>
            <td>Pass word</td>
            <td><?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : 'Chưa nhập'; ?></td>
        </tr>
        <tr>
            <td>Confirm password</td>
            <td><?php echo isset($_POST['ConfirmPass']) ? htmlspecialchars($_POST['ConfirmPass']) : 'Chưa nhập'; ?></td>
        </tr>
        <tr>
            <td>email</td>
            <td><?php echo isset($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : 'Chưa nhập'; ?></td>
        </tr>
        <tr>
            <td>Giới tính</td>
            <td>
                <?php 
                if (isset($_POST["gender"])) { 
                    echo $_POST["gender"] == "male" ? "Nam" : "Nữ"; 
                } else {
                    echo "Chưa chọn";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Ngày sinh</td>
            <td><?php echo isset($_POST['birthday']) ? htmlspecialchars($_POST['birthday']) : 'Chưa nhập'; ?></td>
        </tr>
        <tr>
            <td>địa chỉ</td>
            <td><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : 'Chưa nhập'; ?></td>
        </tr>
        <tr>
            <td>Avatar</td>
            <td>
                <?php if (file_exists($uploadFile)): ?>
                    <img src="<?php echo $uploadFile; ?>" alt="Avatar" style="max-width: 100px;">
                <?php else: ?>
                    Avatar chưa được tải lên.
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Sở thích</td>
            <td>
                <?php
                $hobbies = [];
                if (isset($_POST['film'])) $hobbies[] = "Xem phim";
                if (isset($_POST['cook'])) $hobbies[] = "Nấu ăn";
                if (isset($_POST['game'])) $hobbies[] = "Chơi game";
                if (isset($_POST['football'])) $hobbies[] = "Đá bóng";
                if (isset($_POST['readbook'])) $hobbies[] = "Đọc truyện";

                if (count($hobbies) > 0) {
                    echo implode(', ', $hobbies);
                } else {
                    echo "Không có sở thích nào được chọn.";
                }
                ?>
            </td>
        </tr>
    </table>
    <?php
}
?>

</body>
</html>
