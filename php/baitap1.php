<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Form nhập liệu -->
    <form action="" method="post">
        <div>
            <label for="a">Nhập số a</label>
            <input type="number" name="a" required>
        </div>
        
        <div>
            <label for="b">Nhập số b</label>
            <input type="number" name="b" required>
        </div>
        
        <input type="submit" value="Tính" name="calculator">
    </form>

    <?php
    // Kiểm tra nếu form đã được submit
    if (isset($_POST["calculator"])) {
        $a = isset($_POST['a']) ? (float)$_POST['a'] : 0;
        $b = isset($_POST['b']) ? (float)$_POST['b'] : 0;

        if ($a == 0) {
            if ($b == 0) {
                echo "Phương trình vô số nghiệm (vì 0x + 0 = 0).";
            } else {
                echo "Phương trình vô nghiệm (vì không có giá trị x nào thỏa mãn khi a = 0 và b != 0).";
            }
        } else {
            $x = -$b / $a;
            echo "<br>Phương trình: " . $a . "x + " . $b . " = 0<br>";
            echo "Nghiệm x = " . $x;
        }
    }
    ?>
</body>
</html>
