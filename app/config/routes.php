<?php


use App\Controller\ProductController;
use App\Controller\UserController;
use App\Controller\MainController;
use App\Controller\CartController;


return [
    '/signup' => [UserController::class, 'signup'],
    '/login' => [UserController::class, 'login'],
    '/profile' => [UserController::class, 'profile'],
    '/logout' => [UserController::class, 'logout'],
    '/main' => [MainController::class, 'main'],
    '/cart' => [CartController::class, 'cart'],
    '/add-to-cart' => [CartController::class, 'addProducts'],
    '/deleteProduct' => [CartController::class, 'deleteProduct'],
    '/delete' => [CartController::class, 'delete'],
    '/product' => [ProductController::class, 'product'],
];