<?php
require_once 'utils/cors-handle.php';
require_once 'database/config.php';
require_once 'database/dbhelper.php';
require_once 'utils/utility.php';

try {
    $sql = "SELECT * FROM user where id = $id";
    $user = executeResult($sql, true);
    
    if ($user != null && count($user) > 0) {
        http_response_code(200);
        sendResponse('success', $user, 'Lấy thông tin người dùng thành công!');
    } else {
        http_response_code(404);
        sendResponse('error', null, 'Không tìm thấy thông tin người dùng!');
    }        
} catch (Exception $e) {
    http_response_code(500);
    sendResponse('error', null, 'Đã xảy ra lỗi: ' . $e->getMessage());
}

?>