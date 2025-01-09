<?php
require_once 'utils/cors-handle.php';
require_once 'database/config.php';
require_once 'database/dbhelper.php';
require_once 'utils/utility.php';

try {
    $sql = "SELECT * FROM category where id = " . $id;
    $category = executeResult($sql, true);
    
    if ($category != null && count($category) > 0) {
        http_response_code(200);
        sendResponse('success', $category, 'Lấy thông tin danh mục sản phẩm thành công!');
    } else {
        http_response_code(404);
        sendResponse('error', null, 'Không tìm thấy thông tin danh mục sản phẩm!');
    }        
} catch (Exception $e) {
    http_response_code(500);
    sendResponse('error', null, 'Đã xảy ra lỗi: ' . $e->getMessage());
}

?>