<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* Function for Remove escapes special
 /* characters in a string for use in an SQL statement
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}
/*--------------------------------------------------------------*/
/* Function for Display Session Message
   Ex echo displayt_msg($message);
/*--------------------------------------------------------------*/
function display_msg($msg =''){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}
/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function total_price($totals) {
  $sum = 0;
  $sub = 0;

  foreach ($totals as $total) {
      $sum += isset($total['total_saleing_price']) ? $total['total_saleing_price'] : 0;
      $sub += isset($total['total_buying_price']) ? $total['total_buying_price'] : 0;
  }

  $profit = $sum - $sub;
  return array($sum, $profit);
}

/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date($str){
     if($str)
      return date('F j, Y, g:i:s a', strtotime($str));
     else
      return null;
  }
/*--------------------------------------------------------------*/
/* Function for  Readable Make date time
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* Function for Creting random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}

function find_media_by_name($name) {
  global $db;
  $sql = "SELECT * FROM media WHERE file_name LIKE '%" . $db->escape($name) . "%'";
  return find_by_sql($sql);
}
function find_all_orders_with_items() {
  global $db;
  $sql  = "SELECT o.id AS order_id, o.date, ";
  $sql .= "GROUP_CONCAT(p.name, ' (x', oi.qty, ')') AS products, ";
  $sql .= "SUM(oi.qty * oi.price) AS total ";
  $sql .= "FROM orders o ";
  $sql .= "JOIN order_items oi ON o.id = oi.order_id ";
  $sql .= "JOIN products p ON oi.product_id = p.id ";
  $sql .= "GROUP BY o.id ";
  $sql .= "ORDER BY o.date DESC";
  return find_by_sql($sql);
}
function find_order_items($order_id) {
  global $db;
  $sql  = "SELECT oi.*, p.name AS product_name FROM order_items oi ";
  $sql .= "LEFT JOIN products p ON oi.product_id = p.id ";
  $sql .= "WHERE oi.order_id = '{$db->escape($order_id)}'";
  return find_by_sql($sql);
}

if (!function_exists('format_money')) {
  function format_money($number, $decimals = 0, $dec_point = '.', $thousands_sep = ',') {
    return number_format($number, $decimals, $dec_point, $thousands_sep);
  }
}
function find_low_stock_products($threshold = 20) {
  global $db;
  $sql  = "SELECT id, name, quantity FROM products";
  $sql .= " WHERE quantity <= {$db->escape((int)$threshold)} "; // Bỏ dấu nháy đơn quanh giá trị số
  $sql .= "ORDER BY quantity ASC";
  return find_by_sql($sql);
}

?>
