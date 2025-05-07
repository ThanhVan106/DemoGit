<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giải phương trình bậc 2</title>
</head>
<body>
<h2>Bài 2: Giải phương trình bậc 2</h2>
<p>Nhập giá trị cho phương trình bậc 2: ax² + bx + c = 0</p>

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
    <div>
        <label for="c">Nhập số c</label>
        <input type="number" name="c" required>
    </div>
    <input type="submit" value="Tính" name="calculator">
</form>

<?php
if (isset($_POST["calculator"])) {
    // Lấy giá trị từ form
    $a = isset($_POST['a']) ? (float)$_POST['a'] : 0;
    $b = isset($_POST['b']) ? (float)$_POST['b'] : 0;
    $c = isset($_POST['c']) ? (float)$_POST['c'] : 0;

    // In phương trình ra màn hình
    echo "<br>" . $a . "x² + " . $b . "x + " . $c . " = 0<br>";

    // Xử lý khi a = 0 (phương trình bậc nhất)
    if ($a == 0) {
        if ($b == 0) {
            echo ($c == 0) ? "Phương trình vô số nghiệm." : "Phương trình vô nghiệm.";
        } else {
            $x = -$c / $b;
            echo "Phương trình bậc nhất có nghiệm x = " . $x;
        }
    } else {
        // Tính delta (biệt thức)
        $delta = $b * $b - (4 * $a * $c);

        // Xử lý delta để tìm nghiệm
        if ($delta == 0) {
            $x = -$b / (2 * $a);
            echo "Phương trình có nghiệm kép: x1 = x2 = " . $x;
        } elseif ($delta < 0) {
            echo "Phương trình vô nghiệm (delta < 0).";
        } else {
            $x1 = (-$b + sqrt($delta)) / (2 * $a);
            $x2 = (-$b - sqrt($delta)) / (2 * $a);
            echo "Phương trình có 2 nghiệm phân biệt: x1 = " . $x1 . " và x2 = " . $x2;
        }
    }
}
?>
</body>
</html>
