<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, X-Requested-With');

require_once('../../database/config.php');
require_once('../../database/dbhelper.php');
require_once('../../utils/utility.php');

// Kiểm tra phương thức yêu cầu
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    sendResponse('error', null, 'Phương thức không hợp lệ. Chỉ sử dụng POST.');
}

// Xử lý dữ liệu đầu vào
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if (strpos($contentType, 'application/json') !== false) {
    // Nếu dữ liệu được gửi dưới dạng JSON
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);
    if(is_array($decoded)) {
        $categoryId = isset($decoded['id']) ? trim($decoded['id']) : '';
    } else {
        $categoryId = '';
    }
} else {
    // Nếu dữ liệu được gửi dưới dạng form-data / x-www-form-urlencoded
    $categoryId = getPost('id');
}

// Kiểm tra dữ liệu đầu vào
if(empty($categoryId)) {
    sendResponse('error', null, 'ID danh mục không được để trống.');
}

if(!is_numeric($categoryId)) {
    sendResponse('error', null, 'ID danh mục phải là số.');
}

// Kiểm tra xem danh mục có tồn tại không
$sqlCheckExist = "SELECT * FROM category WHERE id = '$categoryId'";
$existingCategory = executeResult($sqlCheckExist, true);
if ($existingCategory == null) {
    sendResponse('error', null, 'Danh mục không tồn tại.');
}

// Kiểm tra xem có sản phẩm nào thuộc danh mục này không
// Nếu có từ chối xóa
$sqlCheckProducts = "SELECT * FROM product WHERE category_id = '$categoryId'";
$existingProducts = executeResult($sqlCheckProducts, true);
if ($existingProducts != null) {
    sendResponse('error', null, 'Không thể xóa danh mục này vì có sản phẩm thuộc danh mục.');
}

// Thực thi lệnh truy vấn để xóa danh mục
$delete = "DELETE FROM category WHERE id = '$categoryId'";
execute($delete);

// Kiểm tra xem xóa có thành công không
// Do hàm execute không trả về kết quả, chúng ta có thể kiểm tra lại xem danh mục đã bị xóa chưa
$sqlGet = "SELECT * FROM category WHERE id = '$categoryId'";
$deletedCategory = executeResult($sqlGet, true);

if ($deletedCategory == null) {
    sendResponse('success', null, 'Xóa danh mục thành công.');
} else {
    sendResponse('error', null, 'Xóa danh mục thất bại.');
}
?>
