<?php

namespace App\Controller;

use App\Container;
use App\Repository\ProductRepository;
use App\Service\AuthenticateServiceInterface;


class MainController
{

    public function main(): array
    {
        $userAuthenticate = Container::get(AuthenticateServiceInterface::class);
        $user = $userAuthenticate->getUser();

        if ($user === null) {
            header("Location: /login");
        }

        print_r($user->getId());

        $productRepository = Container::get(ProductRepository::class);
        $products = $productRepository->getAll();

        return [
            'view' => 'main',
            'data' => [
                'products' => $products
            ]
        ];
    }

}