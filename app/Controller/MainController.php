<?php

namespace App\Controller;

use App\Model\Product;

class MainController
{
    public function main()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            header('Location :/login');
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