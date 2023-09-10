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
} else {
    require_once "./html/404.html";
}

?>




