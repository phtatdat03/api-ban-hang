<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, X-Requested-With');
 
require_once('../../database/config.php');
require_once('../../database/dbhelper.php');


function sendResponse($status, $data = null, $message = '') {
    $respone = ['status' => $status];
    if ($data != null) {
        $respone['data'] = $data;
    }
    if ($message != '') {
        $respone['message'] = $message;
    }
    echo json_encode($respone);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    sendResponse('error', null, 'Phương thức không hợp lệ. Chỉ sử dụng GET!');
}

$sql = "SELECT * FROM category";
$categories = executeResult($sql);
if ($categories != null && count($categories) > 0) {
    sendResponse('success', $categories, 'Lấy danh sách thành công!');
} else {
    sendResponse('error', null, 'Không tìm thấy danh mục!');
}
?>