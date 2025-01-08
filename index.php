<?php
require_once 'database/config.php';
if (!empty($_SERVER['PATH_INFO'])) {
    $method = $_SERVER['REQUEST_METHOD'];
    $pathname = rtrim($_SERVER['PATH_INFO'], '/');
    $pathnameArr = array_filter(explode('/', $pathname));
    $pathnameArr = array_values($pathnameArr);


// USER METHODS
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


// CATEGORY METHODS
    if ($method ==='GET' && $pathnameArr[0] === 'categories' && empty($pathnameArr[1])) {
        require __DIR__ . '/modules/categories/index.php';
    }

    if ($method ==='GET' && $pathnameArr[0] == 'categories' && !empty($pathnameArr[1])) {
        $id = $pathnameArr[1];
        require __DIR__ . '/modules/categories/detail.php';
    }

    if ($method === 'POST' && $pathnameArr[0] == 'categories' && empty($pathnameArr[1])) {
        require __DIR__ . '/modules/categories/add.php';
    }
}

