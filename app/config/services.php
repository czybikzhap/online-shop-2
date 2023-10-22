<?php

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
    UserController::class => function (Container $container): UserController {
        $obj = $container->get(AuthenticateServiceInterface::class);
        $userRepository = $container->get(UserRepository::class);

        return new UserController($obj, $userRepository);
    },

    MainController::class => function (Container $container): MainController {
        $obj = $container->get(AuthenticateServiceInterface::class);
        $productRepository = $container->get(ProductRepository::class);

    return new MainController($obj, $productRepository);
    },

    CartController::class => function (Container $container): CartController {
        $obj = $container->get(AuthenticateServiceInterface::class);
        $productRepository = $container->get(ProductRepository::class);
        $cartItemRepository = $container->get(CartItemRepository::class);

        return new CartController($obj, $productRepository, $cartItemRepository);
    },

    ProductController::class => function (Container $container): ProductController {
        $obj = $container->get(AuthenticateServiceInterface::class);
        $productRepository = $container->get(ProductRepository::class);

        return new ProductController($obj, $productRepository);
    },

    UserRepository::class => function (Container $container): UserRepository {
        $pdo = $container->get(PDO::class);
        return new UserRepository($pdo);
    },

    ProductRepository::class => function (Container $container): ProductRepository {
        $pdo = $container->get(PDO::class);
        return new ProductRepository($pdo);
    },

    CartItemRepository::class => function (Container $container): CartItemRepository {
        $pdo = $container->get(PDO::class);
        return new CartItemRepository($pdo);
    },

    AuthenticateServiceInterface::class => function (Container $container): AuthenticateServiceInterface {
        $userRepository = $container->get(UserRepository::class);
        return new AuthenticationSessionService($userRepository);
    },

    PDO::class => function(): PDO {
        return new PDO('pgsql:host=db; dbname=dbname',
            'dbuser', 'dbpwd');
    }

];