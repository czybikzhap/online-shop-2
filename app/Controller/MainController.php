<?php

namespace App\Controller;

use app\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\AuthenticateService;

class MainController
{

    private AuthenticateService $authenticateService;

    public function __construct()
    {
        $this->authenticateService = new AuthenticateService();
    }
    public function main(): array
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        print_r($user->getId());

        $products = ProductRepository::getAll();

        return [
            'view' => 'main',
            'data' => [
                'products' => $products
            ]
        ];
    }

}