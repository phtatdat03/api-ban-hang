<?php
require_once 'utils/cors-handle.php';
require_once 'database/config.php';
require_once 'database/dbhelper.php';
require_once 'utils/utility.php';

try {
    $sql = "SELECT user.*, role.name AS role_name FROM user LEFT JOIN role ON user.role_id = role.id WHERE user.deleted = 0";
    $users = executeResult($sql, false);
    
    if ($users != null && count($users) > 0) {
        http_response_code(200);
        sendResponse('success', $users, 'Lấy danh sách người dùng thành công!');
    } else {
        http_response_code(400);
        sendResponse('error', null, 'Lấy danh sách người dùng thất bại!');
    }
} catch (Exception $e) {
    http_response_code(500); // lỗi máy chủ
    sendResponse('error', null, 'Đã xảy ra lỗi: ' . $e->getMessage());
}

?>
