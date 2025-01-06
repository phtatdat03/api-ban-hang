<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, X-Requested-With');
 
require_once('../../database/config.php');
require_once('../../database/dbhelper.php');
 
$method = $_SERVER['REQUEST_METHOD'];
 
$sql = "SELECT user.*, role.name as role_name FROM user LEFT JOIN role ON user.role_id = role.id
        WHERE user.deleted = 0";
 
$users = executeResult($sql);
 
if($users) {
    $response = [
        'status' => 200,
        'message' => 'Lấy danh sách người dùng thành công!',
        'data' => $users
    ];
} else {
    $response = [
        'status' => 404,
        'message' => 'Không tìm thấy dữ liệu',
        'data' => []
    ];
}
 
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>