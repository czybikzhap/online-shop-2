<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\AuthenticateServiceInterface;

class MainController
{

    private AuthenticateServiceInterface $authenticateService;

    private ProductRepository $productRepository;

    public function __construct(AuthenticateServiceInterface $authenticateService,
                                ProductRepository $productRepository)
    {
        $this->authenticateService = $authenticateService;
        $this->productRepository = $productRepository;
    }

    public function main(): array
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        print_r($user->getId());

        $products = $this->productRepository->getAll();

        return [
            'view' => 'main',
            'data' => [
                'products' => $products
            ]
        ];
    }

}