<?php

function handleCors() {

    header("Content-Type: application/json; charset=UTF-8");

    header("Access-Control-Allow-Origin: *");
    
    // Cho phép credentials (nếu bạn gửi cookies hoặc auth headers)
    header("Access-Control-Allow-Credentials: true");
    
    // Các headers được phép
    header("Access-Control-Allow-Headers: Origin, Cache-Control: null, X-Requested-With: null, Content-Type: text/html, Accept, Authorization");
    
    // Các methods được phép
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    
}


handleCors();


?>