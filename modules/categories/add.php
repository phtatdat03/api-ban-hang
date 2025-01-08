<?php

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, X-Requested-With');
require_once 'database/config.php';
require_once 'database/dbhelper.php';
require_once 'utils/utility.php';

$body = file_get_contents('php://input');
$body = json_decode($body, true);

try{

    //validate
    $errors = [];
    if (!isset($body['name']) || isset($body['name']) && $body['name'] === '') {
        $errors['name'] = 'Tên không được để trống!';
    } else {
        if (!preg_match('/^[a-zA-ZÀ-ỹ\s]+$/', $body['name'])) {
            $errors['name'] = 'Tên không được chứa ký tự đặc biệt!';
        }
    }

    if (!empty($errors)) {
        http_response_code(400);
        sendResponse('false', $errors, 'Validate Failed!');
    } else {
        $name = $body['name'];

        $checkCategory = "SELECT id FROM category WHERE name ='$name'";
        $existingUser = executeResult($checkCategory, true);
        if($existingUser != null) {
            http_response_code(400);
            sendResponse('false', null, 'Danh mục sản phẩm đã tồn tại!');
            exit;
        }

        $sql = "INSERT INTO category(name) VALUES ('$name')";
        $insertStatus = execute($sql);
        if ($insertStatus) {
            http_response_code(200);
            sendResponse('success', null, 'Thêm danh mục sản phẩm thành công!');
        }
        else {
            http_response_code(400);
            sendResponse('failed', null, 'Thêm danh mục sản phẩm thất bại!');
        }
    }
} catch (Exception $e){
    http_response_code(500);
    sendResponse('false', null, 'Đã xảy ra lỗi: ' . $e->getMessage());
}

?>