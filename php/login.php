<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <form action="" method="post">
        <table border="1" style="width: 600px;">
        <tr>
            <td colspan="2" style="text-align: center;font-size: 24px;font-weight: bold;">Nhập thông tin</td>
        </tr>
        <tr>
            <td>Full name</td>
            <td><input class="input-info"  type="text" placeholder="Nhập name" name="fullname"></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><input class="input-info" type="tel" placeholder="Nhập số điện thoại" name="tel"></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input class="input-info" type="email" placeholder="Nhập email" name="email"></td>
        </tr>
        <tr>
            <td>địa chỉ</td>
            <td><textarea class="input-info" name="address" ></textarea></td>
        </tr>
        <tr>
           <td colspan="2">
            <div class="" style="align-items: center;text-align: center;">
                <input name="show" type="submit" value="Hiện thị">
            </div>
           </td> 
        </tr>
    </table>
    </form>
</body>
</html>
<?php
if(isset($_POST["show"]) && $_POST["show"]){
    $info=["fullname"=>$_POST["fullname"],"phone"=>$_POST["tel"],"email"=>$_POST["email"],"address"=>$_POST["address"]];
    foreach($info as $k=>$v){
        echo $k.": ".$v."<br>";
    }
}
?>