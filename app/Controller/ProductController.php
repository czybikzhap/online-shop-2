<?php

namespace App\Controller;

use App\Model\Product;

class ProductController
{
    public function product()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            header('Location :/login');
        }
        $id = $_POST['product_id'];

        $product = Product::getById($id);

        return [
            'view' => 'product',
            'data' => [
                'product' => $product
            ]
        ];
    }
}