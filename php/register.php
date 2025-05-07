<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form đăng ký</title>
</head>
<body>

<!-- Form đăng ký -->
<form action="result.php" method="post" enctype="multipart/form-data">
    <table border="1" style="width: 600px;">
        <tr>
            <td colspan="2" style="text-align: center; font-size: 24px; font-weight: bold;">Đăng kí</td>
        </tr>
        <tr>
            <td>Full name</td>
            <td><input type="text" name="fullname" required> 
        </td>
        </tr>
        <tr>
            <td>User name</td>
            <td><input type="text" name="username" required></td>
        </tr>
        <tr>
            <td>Pass word</td>
            <td><input type="password" name="password" required></td>
        </tr>
        <tr>
            <td>Confirm password</td>
            <td><input type="password" name="ConfirmPass" required></td>
        </tr>
        <tr>
            <td>email</td>
            <td><input type="email" name="user_email" required></td>
        </tr>
        <tr>
            <td>Giới tính</td>
            <td>
                <input type="radio" name="gender" value="male" required> Nam
                <input type="radio" name="gender" value="female" required> Nữ
            </td>
        </tr>
        <tr>
            <td>Ngày sinh</td>
            <td><input type="date" name="birthday" required></td>
        </tr>
        <tr>
            <td>địa chỉ</td>
            <td><input type="text" name="address" required></td>
        </tr>
        <tr>
            <td>Avatar</td>
            <td><input type="file" name="avatar" required></td>
        </tr>
        <tr>
            <td>Sở thích</td>
            <td>
                <input type="checkbox" name="film" value="Xem phim"> Xem phim
                <input type="checkbox" name="cook" value="Nấu ăn"> Nấu ăn
                <input type="checkbox" name="game" value="Chơi game"> Chơi game
                <input type="checkbox" name="football" value="Đá bóng"> Đá bóng
                <input type="checkbox" name="readbook" value="Nấu ăn"> Đọc truyện
   

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <!-- Reset Button -->
                <input type="reset" value="Reset">
                <!-- Submit Button -->
                <input type="submit" name="submit" value="Đăng ký">
            </td>
        </tr>
    </table>
</form>

</body>
</html>
