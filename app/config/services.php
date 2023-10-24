<?php

use App\Container;
use App\Controller\CartController;
use App\Controller\MainController;
use App\Controller\ProductController;
use App\Controller\UserController;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\AuthenticationSessionService;
use App\Service\AuthenticateServiceInterface;


return  [
    UserController::class => function (): UserController {
        Container::get(AuthenticateServiceInterface::class);
        Container::get(UserRepository::class);

        return new UserController();
    },

    MainController::class => function (): MainController {
        Container::get(AuthenticateServiceInterface::class);
        Container::get(ProductRepository::class);

    return new MainController();
    },

    CartController::class => function (): CartController {
        Container::get(AuthenticateServiceInterface::class);
        Container::get(ProductRepository::class);
        Container::get(CartItemRepository::class);

        return new CartController();
    },

    ProductController::class => function (): ProductController {
        Container::get(AuthenticateServiceInterface::class);
        Container::get(ProductRepository::class);

        return new ProductController();
    },

    UserRepository::class => function (): UserRepository {
        $pdo = Container::get(PDO::class);
        return new UserRepository($pdo);
    },

    ProductRepository::class => function (): ProductRepository {
        $pdo = Container::get(PDO::class);
        return new ProductRepository($pdo);
    },

    CartItemRepository::class => function (): CartItemRepository {
        $pdo = Container::get(PDO::class);
        return new CartItemRepository($pdo);
    },

    AuthenticateServiceInterface::class => function (): AuthenticateServiceInterface {
        $userRepository = Container::get(UserRepository::class);
        return new AuthenticationSessionService($userRepository);
    },

    PDO::class => function(): PDO {
        return new PDO('pgsql:host=db; dbname=dbname',
            'dbuser', 'dbpwd');
    }

];