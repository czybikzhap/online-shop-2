<?php

namespace App\Controller;

use App\Model\Product;
use App\Service\AuthenticateService;

class MainController
{

//    private AuthenticateService $authenticateService;
//
//    public function __costruct()
//    {
//        $this->authenticateService = new AuthenticateService();
//    }
    public function main(): array
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        print_r($_SESSION['id']);


        $products = Product::getAll();

        return [
            'view' => 'main',
            'data' => [
                'products' => $products
            ]
        ];
    }
}