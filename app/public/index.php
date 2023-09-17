<?php

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/signup') {
    require_once "./handlers/signup.php";
} elseif ($uri === '/login') {
    require_once "./handlers/login.php";
} elseif ($uri === '/main') {
    require_once "./handlers/main.php";

    http_response_code(403);
} elseif ($uri === '/logout') {
    session_start();
    session_destroy();
}  elseif ($uri === '/cart') {
    require_once "./handlers/cart.php";
} elseif ($uri === '/add-to-cart') {
    require_once "./handlers/add-to-cart.php";
} elseif ($uri === '/profile') {
    require_once "./handlers/profile.php";
} {
    require_once "./html/404.html";
}

?>




