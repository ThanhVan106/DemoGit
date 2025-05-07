<?php $user = current_user(); ?>
<?php
  // Lấy danh sách hàng sắp hết
  $low_stock_products = find_low_stock_products(20); // SL <= 20
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php if (!empty($page_title))
       echo remove_junk($page_title);
        elseif(!empty($user))
       echo ucfirst($user['name']);
        else echo "Hệ thống Quản lý Bán hàng";?>
</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<style>
  .badge.bg-red {
    background-color: red;
    color: white;
    position: absolute;
    top: 8px;
    right: 0px;
    font-size: 10px;
  }
  .notifications .dropdown-menu {
    width: 250px;
    padding: 10px;
  }
  .notifications .dropdown-menu li a {
    white-space: normal;
  }
</style>
</head>
<body>
<?php  if ($session->isUserLoggedIn(true)): ?>
<header id="header">
  <div class="logo pull-left"> Hệ thống Bán hàng</div>
  <div class="header-content">
  <div class="header-date pull-left">
    <strong><?php echo date("F j, Y, g:i a");?></strong>
  </div>
  <div class="pull-right clearfix">
    <ul class="info-menu list-inline list-unstyled">

      <!-- Thông báo hàng sắp hết -->
      <li class="notifications dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Hàng sắp hết">
          <i class="glyphicon glyphicon-bell"></i>
          <?php if(count($low_stock_products) > 0): ?>
            <span class="badge bg-red"><?php echo count($low_stock_products); ?></span>
          <?php endif; ?>
        </a>
        <ul class="dropdown-menu">
          <li class="header text-center"><strong>Hàng sắp hết</strong></li>
          <?php if(count($low_stock_products) > 0): ?>
            <?php foreach ($low_stock_products as $prod): ?>
  <li style="padding: 5px 10px; margin-bottom: 5px; background-color: #fff3cd; border-left: 5px solid #ffc107; border-radius: 4px;">
    <span style="color: #856404; font-weight: bold;">
      ⚠ <?php echo $prod['name']; ?> (TK: <?php echo $prod['quantity']; ?>)
    </span>
  </li>
<?php endforeach; ?>
          <?php else: ?>
          <li><a class="text-center">Tất cả hàng còn đủ</a></li>
        <?php endif; ?>
        </ul>
      </li>

      <!-- Hồ sơ người dùng -->
      <li class="profile">
        <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
          <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
          <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
        </a>
        <ul class="dropdown-menu">
          <li>
              <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                  <i class="glyphicon glyphicon-user"></i>
                  Hồ sơ
              </a>
          </li>
         <li>
             <a href="edit_account.php" title="edit account">
                 <i class="glyphicon glyphicon-cog"></i>
                 Cài đặt
             </a>
         </li>
         <li class="last">
             <a href="logout.php">
                 <i class="glyphicon glyphicon-off"></i>
                 Đăng xuất
             </a>
         </li>
       </ul>
      </li>
    </ul>
  </div>
 </div>
</header>
<div class="sidebar">
  <?php if($user['user_level'] === '1'): ?>
    <?php include_once('admin_menu.php');?>
  <?php elseif($user['user_level'] === '2'): ?>
    <?php include_once('special_menu.php');?>
  <?php elseif($user['user_level'] === '3'): ?>
    <?php include_once('user_menu.php');?>
  <?php elseif($user['user_level'] === '4'): ?>
    <?php include_once('wakehouse_menu.php');?>
  <?php endif;?>
</div>
<?php endif;?>
<div class="page">
  <div class="container-fluid">
