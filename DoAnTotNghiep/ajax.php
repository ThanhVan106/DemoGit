<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) { redirect('index.php', false); }
?>

<?php
// Gợi ý tự động
$html = '';
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
    $products = find_product_by_title($_POST['product_name']); // Tìm kiếm sản phẩm theo tên
    if ($products) {
        foreach ($products as $product) {
            $html .= "<li class=\"list-group-item\">";
            $html .= $product['name']; // Hiển thị tên sản phẩm
            $html .= "</li>";
        }
    } else {
        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">'; // Hiển thị thông báo không tìm thấy sản phẩm
        $html .= 'Không tìm thấy';
        $html .= "</li>";
    }
    echo json_encode($html);
}
?>

<?php
// Tìm tất cả sản phẩm
if (isset($_POST['p_name']) && strlen($_POST['p_name'])) {
    $product_title = remove_junk($db->escape($_POST['p_name']));
    if ($results = find_all_product_info_by_title($product_title)) {
        foreach ($results as $result) {
            $html .= "<tr>";
            $html .= "<td id=\"s_name\">".$result['name']."</td>"; // Hiển thị tên sản phẩm
            $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">"; // Input ẩn chứa ID sản phẩm
            $html .= "<td>";
            $html .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['sale_price']}\">"; // Hiển thị giá bán
            $html .= "</td>";
            $html .= "<td id=\"s_qty\">";
            $html .= "<input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\">"; // Hiển thị số lượng mặc định là 1
            $html .= "</td>";
            $html .= "<td>";
            $html .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\">"; // Hiển thị tổng tiền, mặc định là giá bán
            $html .= "</td>";
            $html .= "<td>";
            $html .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">"; // Hiển thị ô nhập ngày, định dạng yyyy-mm-dd
            $html .= "</td>";
            $html .= "<td>";
            $html .= "<button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Thêm vào</button>"; // Nút thêm sản phẩm vào đơn hàng
            $html .= "</td>";
            $html .= "</tr>";
        }
    } else {
        $html = '<tr><td>Tên sản phẩm không tồn tại trong cơ sở dữ liệu</td></tr>'; // Hiển thị thông báo khi không tìm thấy sản phẩm
    }
    echo json_encode($html);
}
?>
