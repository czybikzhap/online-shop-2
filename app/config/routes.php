<?php


use App\Controller\ProductController;
use App\Controller\UserController;
use App\Controller\MainController;
use App\Controller\CartController;


return [
    '/signup' => [UserController::class, 'signup'],
    '/login' => [UserController::class, 'login'],
    '/profile' => [UserController::class, 'profile'],
    '/main' => [MainController::class, 'main'],
    '/cart' => [CartController::class, 'cart'],
    '/add-to-cart' => [CartController::class, 'addProducts'],
    '/product' => [ProductController::class, 'product'],
];