<?php

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, X-Requested-With');
require_once 'database/config.php';
require_once 'database/dbhelper.php';
require_once 'utils/utility.php';


try{
    $body = file_get_contents('php://input');
    $body = json_decode($body, true);
    
    //validate
    $errors = [];
    if (!isset($body['fullname']) || isset($body['fullname']) && $body['fullname'] === '') {
        $errors['fullname'] = 'Tên không được để trống!';
    } else {
        if (!preg_match('/^[a-zA-ZÀ-ỹ\s]+$/', $body['fullname'])) {
            $errors['fullname'] = 'Tên không được chứa ký tự đặc biệt!';
        }
    }
    
    if (!isset($body['email']) || isset($body['email']) && $body['email'] === '') {
        $errors['email'] = 'Email không được để trống!';
    } elseif (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email Không đúng định dạng!';
    }
    
    if (!isset($body['phone_number']) || isset($body['phone_number']) && $body['phone_number'] === '') {
        $errors['phone_number'] = 'Số điện thoại không được để trống!';
    } else {
        $phone = preg_replace('/\s+/', '', $body['phone_number']);
        $pattern = '/^(03|05|07|08|09|02)\d{8}$/';
        if (!preg_match($pattern, $phone)) {
            $errors['phone_number'] = 'Số điện thoại không đúng định dạng! Vui lòng nhập số điện thoại hợp lệ.';
        }
    }
    
    if (!isset($body['address']) || isset($body['address']) && $body['address'] === '') {
        $errors['address'] = 'Địa chỉ không được để trống!';
    }
    
    if (!isset($body['password']) || isset($body['password']) && $body['password'] === '') {
        $errors['password'] = 'Mật khẩu không được để trống!';
    }
    
    if (!empty($errors)) {
        http_response_code(400);
        sendResponse('false', $errors, 'Validate Failed!');
    } else {
        $fullname = $body['fullname'];
        $email = $body['email'];
        $phone_number = $body['phone_number'];
        $address = $body['address'];
        $password = $body['password'];
        if ($password != '') {
            $password = getSecurityMD5($password);
        }
        $created_at =$updated_at = date("Y-m-d H:i:s");
        $role_id = isset($body['role_id']) ? $body['role_id'] : 2; //Role mặc định là USER
        $deleted = isset($body['deleted']) ? (int) $body['deleted'] : 0;

        $checkEmail = "SELECT id FROM user WHERE email ='$email' AND deleted = 0";
        $existingUser = executeResult($checkEmail, true);
        if($existingUser != null) {
            http_response_code(400);
            sendResponse('false', null, 'Email đã tồn tại!');
            exit;
        }

        $sql = "INSERT INTO user(fullname, email, phone_number, 
                                address, password, role_id, created_at, updated_at, deleted)
                            VALUES ('$fullname', '$email', '$phone_number', '$address', '$password', '$role_id', '$created_at', '$updated_at', '$deleted')";
        $insertStatus = execute($sql);
        if ($insertStatus) {
            http_response_code(200);
            sendResponse('success', null, 'Thêm người dùng thành công!');
        }
        else {
            http_response_code(400);
            sendResponse('failed', null, 'Thêm người dùng thất bại!');
        }
    }
} catch (Exception $e){
    http_response_code(500);
    sendResponse('false', null, 'Đã xảy ra lỗi: ' . $e->getMessage());
}

?>