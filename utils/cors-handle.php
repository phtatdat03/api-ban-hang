<?php

function handleCors() {

    header("Content-Type: application/json; charset=UTF-8");

    header("Access-Control-Allow-Origin: *");
    // Các headers được phép
    header("Access-Control-Allow-Headers: *");
    // Cho phép credentials (nếu bạn gửi cookies hoặc auth headers)
    header("Access-Control-Allow-Credentials: true");
    // Các methods được phép
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    
}

handleCors();


?>