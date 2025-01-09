<?php

require_once 'utils/cors-handle.php';
require_once 'database/config.php';
require_once 'database/dbhelper.php';
require_once 'utils/utility.php';

try {
    $sql = "SELECT * FROM category";
    $categories = executeResult($sql, false);
    
    if ($categories != null && count($categories) > 0) {
        http_response_code(200);
        sendResponse('success', $categories, 'Lấy danh sách danh mục sản phẩm thành công!');
    } else {
        http_response_code(400);
        sendResponse('error', null, 'Lấy danh sách danh mục sản phẩm thất bại!');
    }
} catch (Exception $e) {
    http_response_code(500); // lỗi máy chủ
    sendResponse('error', null, 'Đã xảy ra lỗi: ' . $e->getMessage());
}

?>
