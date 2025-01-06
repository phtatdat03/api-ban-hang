<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, X-Requested-With');

require_once('../../database/config.php');
require_once('../../database/dbhelper.php');
require_once('../../utils/utility.php');

//kiểm tra phương thức yêu cầu
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
        $categoryName = isset($decoded['name']) ? trim($decoded['name']) : '';
    } else {
        $categoryId = '';
        $categoryName = '';
    }
} else {
    // Nếu dữ liệu được gửi dưới dạng form-data / x-www-form-urlencoded
    $categoryId = getPost('id');
    $categoryName = getPost('name');
}

// Kiểm tra dữ liệu đầu vào
if(empty($categoryId)) {
    sendResponse('error', null, 'ID danh mục không được để trống.');
}

if(!is_numeric($categoryId)) {
    sendResponse('error', null, 'ID danh mục phải là số.');
}

if(empty($categoryName)) {
    sendResponse('error', null, 'Tên danh mục không được để trống.');
}

if(strlen($categoryName) > 255) {
    sendResponse('error', null, 'Tên danh mục không được vượt quá 255 ký tự.');
}

// Kiểm tra xem danh mục có tồn tại không
$sqlCheckExist = "SELECT * FROM category WHERE id = '$categoryId'";
$existingCategory = executeResult($sqlCheckExist, true);
if ($existingCategory == null) {
    sendResponse('error', null, 'Danh mục không tồn tại.');
}

// Kiểm tra xem tên danh mục mới đã tồn tại chưa (ngoại trừ danh mục hiện tại)
$sqlCheckName = "SELECT * FROM category WHERE name = '$categoryName' AND id != '$categoryId'";
$duplicateCategory = executeResult($sqlCheckName, true);
if ($duplicateCategory != null) {
    sendResponse('error', null, 'Tên danh mục đã tồn tại.');
}

// Thực thi lệnh truy vấn để cập nhật tên danh mục
$update = "UPDATE category SET name = '$categoryName' WHERE id = '$categoryId'";
execute($update);

// Lấy thông tin danh mục đã được cập nhật để phản hồi
$sqlGet = "SELECT * FROM category WHERE id = '$categoryId'";
$updatedCategory = executeResult($sqlGet, true);

if ($updatedCategory != null) {
    sendResponse('success', $updatedCategory, 'Cập nhật danh mục thành công.');
} else {
    sendResponse('error', null, 'Cập nhật danh mục thất bại.');
}
?>