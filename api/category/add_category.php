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
        $categoryName = isset($decoded['name']) ? trim($decoded['name']) : '';
    } else {
        $categoryName = '';
    }
} else {
    // Nếu dữ liệu được gửi dưới dạng form-data / x-www-form-urlencoded
    $categoryName = getPost('name');
}

// Kiểm tra dữ liệu đầu vào
if(empty($categoryName)) {
    sendResponse('error', null, 'Tên danh mục không được để trống.');
}

// Kiểm tra xem danh mục đã tồn tại chưa
$sqlCheck = "SELECT * FROM category WHERE name = '$categoryName'";
$existingCategory = executeResult($sqlCheck, true);
if ($existingCategory != null) {
    sendResponse('error', null, 'Danh mục đã tồn tại.');
}

// Thực thi lệnh truy vấn để thêm mới
$insert = "INSERT INTO category(name) VALUES ('$categoryName')";
execute($insert);

// Lấy thông tin danh mục vừa thêm để phản hồi
$sqlGet = "SELECT * FROM category WHERE name = '$categoryName' ORDER BY id DESC LIMIT 1";
$newCategory = executeResult($sqlGet, true);

if ($newCategory != null) {
    sendResponse('success', $newCategory, 'Thêm danh mục thành công.');
} else {
    sendResponse('error', null, 'Thêm danh mục thất bại.');
}
?>