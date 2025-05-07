
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="number" value="nhập tiền rút" name="monney">
        <div class="">
            <input type="submit" name="r50k" value="rút tiền 50 k">
            <input type="submit" name="r100k" value="rút tiền 100 k">
            <input type="submit" name="r200k" value="rút tiền 200 k">
            <input type="submit" name="rd" value="rút tiền ngẫu nhiên">
        </div>
    </form>
    <?php 
function t5($money=0){
    $r=[];
if($money>=50000){
$x=round($money/50000);
$y=$money%50000;
$r[0]=$x;
$r[1]=$y;
}else{
$r[0]="số dư không đủ<br>";
$r[1]=$money;
}
return $r;
}
function t100($money=0){
    $r=[];
if($money>=100000){
$x=round($money/100000);
$y=$money%100000;
$r[0]=$x;
$r[1]=$y;
}else{
$r[0]="số dư không đủ<br>";
$r[1]=$money;
}
return $r;
}
function t200($money=0){
    $r=[];
if($money>=200000){
$x=round($money/200000);
$y=$money%200000;
$r[0]=$x;
$r[1]=$y;
}else{
$r[0]="số dư không đủ<br>";
$r[1]=$money;
}
return $r;
}
function tr($money=0){
    $r=[];
    $du=0;
    if($money>=50000){
        if($money>=200000){
            $a=t200($money);
            $r[0]=$a[0];
            $du=$a[1];
           if($money>=100000){
               $b=t100($du);
               $r[1]=$b[0];
               $du=$b[1];
               if($money>= 50000){
                   $c=t5($du);
                   $r[2]=$c[0];
                   $du=$c[1];
                   $r[3]=$du;
               }
           }
           }
        }else{
            $r[0]=0;
            $r[1]=0;
            $r[2]=0;
            $r[3]=$money;
        }

return $r;
}
if(isset($_POST["r50k"]) && $_POST["r50k"]){
    $money=$_POST["monney"];
    $r=t5($money);
    echo "số tờ 50k :".$r[0];
    echo"<br>tiền thừa ".$r[1];
}
if(isset($_POST["r100k"]) && $_POST["r100k"]){
    $money=$_POST["monney"];
    $r=t100($money);
    echo "số tờ 100k :".$r[0];
    echo"<br>tiền thừa ".$r[1];
}
if(isset($_POST["r200k"]) && $_POST["r200k"]){
    $money=$_POST["monney"];
    $r=t200($money);
    echo "số tờ 200k :".$r[0];
    echo"<br>tiền thừa ".$r[1];
}
if(isset($_POST["rd"]) && $_POST["rd"]){
    $money=$_POST["monney"];
    $r=tr($money);
    echo "số tờ 200k :".$r[0];
     echo "<br>số tờ 100k :".$r[1];
     echo "<br>số tờ 50k :".$r[2];
     echo"<br>tiền thừa ".$r[3];
}
?>

</body>
</html>