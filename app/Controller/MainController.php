<?php

namespace App\Controller;

use App\Model\Product;
use App\Model\User;
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

        $products = Product::getAll();

        return [
            'view' => 'main',
            'data' => [
                'products' => $products
            ]
        ];
    }

}