<?php

use App\Controller\CartController;
use App\Controller\MainController;
use App\Controller\ProductController;
use App\Controller\UserController;
use App\Service\AuthenticationSessionService;

return  [
    UserController::class => function(): UserController {
        $obj = new AuthenticationSessionService();

        return new UserController($obj);
    },
    MainController::class => function(): MainController {
    $obj = new AuthenticationSessionService();

    return new MainController($obj);
    },
    CartController::class => function(): CartController {
        $obj = new AuthenticationSessionService();

        return new CartController($obj);
    },
    ProductController::class => function(): ProductController {
        $obj = new AuthenticationSessionService();

        return new ProductController($obj);
    },

];