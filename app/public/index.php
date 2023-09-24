<?php

$uri = $_SERVER['REQUEST_URI'];

spl_autoload_register(function (string $className) {
    require_once "../Controller/$className.php";
});

$routes = [
    '/signup' => [
        'class' => 'UserController',
        'method' => 'signup'
    ],
    '/login' => [
        'class' => 'UserController',
        'method' => 'login'
    ],
    '/main' => [
        'class' => 'MainController',
        'method' => 'main'
    ],
    '/cart' => [
        'class' => 'CartController',
        'method' => 'cart'
    ],
    '/addProduct' => [
        'class' => 'CartController',
        'method' => 'addProduct'
    ],
    '/profile' => [
        'class' => 'UserController',
        'method' => 'profile'
    ],
];

if (isset($routes[$uri])) {
    $handlers = $routes[$uri];

    $class = $handlers['class'];
    $method = $handlers['method'];

    $obj = new $class();
    $obj->$method();

} else {
    require_once '../Views/404.html';
};

//if ($uri === '/signup') {
//    $controller = new UserController();
//    $controller->signup();
//} elseif ($uri === '/login') {
//    $controller = new UserController();
//    $controller->login();
//} elseif ($uri === '/main') {
//    $controller = new MainController();
//    $controller->main();
//
//    http_response_code(403);
//} elseif ($uri === '/logout') {
//    session_start();
//    session_destroy();
//}  elseif ($uri === '/cart') {
//    $controller = new CartController();
//    $controller->cart();
//} elseif ($uri === '/add-to-cart') {
//    $controller = new CartController();
//    $controller->addProduct();
//} elseif ($uri === '/profile') {
//    $controller = new UserController();
//    $controller->profile();
//} else {
//    require_once "../Views/404.html";
//}

?>




