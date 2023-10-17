<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\AuthenticateService;
use App\Service\AuthenticationCookieService;

class MainController
{

    private AuthenticationCookieService $authenticateService;

    public function __construct()
    {
        $this->authenticateService = new AuthenticationCookieService();
    }
    public function main(): array
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        print_r($user->getId());

        $products = new ProductRepository;
        $products = $products->getAll();

        return [
            'view' => 'main',
            'data' => [
                'products' => $products
            ]
        ];
    }

}