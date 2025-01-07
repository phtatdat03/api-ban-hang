<?php
require_once 'database/config.php';
if (!empty($_SERVER['PATH_INFO'])) {
    $method = $_SERVER['REQUEST_METHOD'];
    $pathname = rtrim($_SERVER['PATH_INFO'], '/');
    $pathnameArr = array_filter(explode('/', $pathname));
    $pathnameArr = array_values($pathnameArr);

    if ($method ==='GET' && $pathnameArr[0] === 'users' && empty($pathnameArr[1])) {
        require __DIR__ . '/modules/users/index.php';
    }

    if ($method ==='GET' && $pathnameArr[0] == 'users' && !empty($pathnameArr[1])) {
        $id = $pathnameArr[1];
        require __DIR__ . '/modules/users/detail.php';
    }

    if ($method === 'POST' && $pathnameArr[0] == 'users' && empty($pathnameArr[1])) {
        require __DIR__ . '/modules/users/add.php';
    }

    if ($method === 'PATCH' && $pathnameArr[0] == 'users' && !empty($pathnameArr[1])) {
        $id = $pathnameArr[1];
        require __DIR__ . '/modules/users/update.php';
    }

    if ($method === 'PATCH' && $pathnameArr[0] == 'users' && !empty($pathnameArr[1])) {
        $id = $pathnameArr[1];
        require __DIR__ . '/modules/users/delete.php';
    }
}

//Mở phpmyadmin: http://127.0.0.1:8080/phpmyadmin/

// http://localhost:8080/users ==> lấy danh sách users
// http://localhost:8080/users/1 ==> lấy 1 user