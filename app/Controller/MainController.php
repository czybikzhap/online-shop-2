<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\AuthenticateServiceInterface;

class MainController
{

    private AuthenticateServiceInterface $authenticateService;

    public function __construct(AuthenticateServiceInterface $authenticateService)
    {
        $this->authenticateService = $authenticateService;
    }

    public function main(): array
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        print_r($user->getId());

        $productsRepository = new ProductRepository;
        $products = $productsRepository->getAll();

        return [
            'view' => 'main',
            'data' => [
                'products' => $products
            ]
        ];
    }

}