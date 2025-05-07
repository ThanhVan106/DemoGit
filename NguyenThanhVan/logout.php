<?php
  // Import các file cần thiết
  require_once('includes/load.php');
  
  // Nếu logout không thành công, chuyển hướng về trang index.php
  if(!$session->logout()) {
    redirect("index.php");
  }
?>
