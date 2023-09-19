<?php


$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/signup') {
    require_once "../Controller/UserController.php";
    $controller = new UserController();
    $controller->signup();
} elseif ($uri === '/login') {
    require_once "../Controller/UserController.php";
    $controller = new UserController();
    $controller->login();
} elseif ($uri === '/main') {
    require_once "../Controller/MainController.php";
    $controller = new MainController();
    $controller->main();

    http_response_code(403);
} elseif ($uri === '/logout') {
    session_start();
    session_destroy();
}  elseif ($uri === '/cart') {
    require_once "../Controller/CartController.php";
    $controller = new CartController();
    $controller->cart();
} elseif ($uri === '/add-to-cart') {
    require_once "../Controller/CartController.php";
    $controller = new CartController();
    $controller->addProduct();
} elseif ($uri === '/profile') {
    require_once "../Controller/UserController.php";
    $controller = new UserController();
    $controller->profile();
} else {
    require_once "./html/404.html";
}

?>




