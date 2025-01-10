<?php
require_once 'utils/cors-handle.php';
require_once 'database/config.php';
require_once 'database/dbhelper.php';
require_once 'utils/utility.php';

try {
    // Kiểm tra user tồn tại và lấy thông tin role
    $checkSql = "SELECT role_id FROM user WHERE id = $id";
    $userInfo = executeResult($checkSql, true);

    if (!$userInfo) {
        http_response_code(404);
        sendResponse('failed', null, 'Người dùng không tồn tại!');
        exit();
    }

    // Kiểm tra role_id
    if ($userInfo['role_id'] == 1) {
        http_response_code(404);
        sendResponse('failed', null, 'Không thể xóa người dùng có role Admin!');
        exit();
    }

    // Thực hiện xóa nếu passed tất cả điều kiện
    $sql = "DELETE FROM user WHERE id = $id";
    $deleteStatus = execute($sql);

    if ($deleteStatus) {
        http_response_code(200);
        sendResponse('success', null, 'Xóa người dùng thành công!');
    } else {
        http_response_code(404);
        sendResponse('failed', null, 'Xóa người dùng thất bại!');
    }

} catch(Exception $e) {
    http_response_code(500);
    sendResponse('failed', null, 'Đã xảy ra lỗi: ' . $e->getMessage());
}
?>