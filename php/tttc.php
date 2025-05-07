<?php
function checkSolution($x, $y, $z) {
    return ($x + $y + $z == 100 && 15 * $x + 9 * $y + $z == 900);
}

// Hàm in ra một bộ nghiệm
function printSolution($x, $y, $z) {
    echo "Số trâu đứng: $x\n";
    echo "Số trâu nằm: $y\n";
    echo "Số trâu già: $z\n\n";
}

// Khởi tạo khoảng giá trị cho x, y, z
$max_value = 100;

// Duyệt qua tất cả các giá trị có thể của x, y, z
for ($x = 0; $x <= $max_value; $x++) {
    for ($y = 0; $y <= $max_value - $x; $y++) {
        $z = 100 - $x - $y;
        if (checkSolution($x, $y, $z)) {
            printSolution($x, $y, $z);
           echo"<br>";
        }
    }
}


