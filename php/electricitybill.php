
<!DOCTYPE html>
<html lang="en">
<>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    <h1>TÍNH TIỀN ĐIỆN</h1>
    <div>
        <label for="">Tên chủ hộ</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label for="">Số điện ban đầu</label>
        <input type="number" name="ibefore" required>
    </div>
    <div>
        <label for="">Số điện cuối</label>
        <input type="number" name="iafter" required>
    </div>
    <input type="submit" value="Tính" name="calculator">
</form>

    <?php
function calculateElectricity($ibefore = 0, $iafter = 0) {
    $iuse = $iafter - $ibefore; // Số điện sử dụng
    $totalCost = 0;

    // Kiểm tra nếu có sử dụng điện
    if ($iuse > 0) {
        if ($iuse >= 100) {
            $totalCost += 100 * 1250;
            $iuse -= 100;

            if ($iuse >= 50) {
                $totalCost += 50 * 1550;
                $iuse -= 50;

                if ($iuse >= 50) {
                    $totalCost += 50 * 1850;
                    $iuse -= 50;

                    if ($iuse > 0) {
                        $totalCost += $iuse * 2250;
                    }
                } else {
                    $totalCost += $iuse * 1850;
                }
            } else {
                $totalCost += $iuse * 1550;
            }
        } else {
            $totalCost += $iuse * 1250;
        }
    } else {
        echo "Lỗi: Số điện sau phải lớn hơn số điện trước!";
        return 0;
    }

    return $totalCost;
}

if (isset($_POST["calculator"])) {
    $name = htmlspecialchars($_POST["name"]);
    $ibefore = (float)$_POST["ibefore"];
    $iafter = (float)$_POST["iafter"];
    
    // Tính tiền điện
    $cost = calculateElectricity($ibefore, $iafter);
    $usedElectricity = $iafter - $ibefore;

    // Hiển thị kết quả
    echo "Chủ hộ: " . $name . "<br>";
    echo "Số điện đầu kỳ: " . $ibefore . "<br>";
    echo "Số điện cuối kỳ: " . $iafter . "<br>";
    echo "Số điện đã sử dụng: " . $usedElectricity . " kWh<br>";
    echo "Tổng tiền điện phải trả: " . number_format($cost, 0, '.', ',') . " VND<br>";
}
?>
