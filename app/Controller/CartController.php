<?php

namespace App\Controller;

use App\Model\Cart;

class CartController
{
    public function cart(): array
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            header('Location :/login');
        }

        $userId = $_SESSION['id'];

        $cart = Cart::getProductsInCart($userId);
        //print_r($cart);
        $productsWithKeyId = Cart::productsWithKeyId($cart);

        return [
            'view' => 'cart',
            'data' => [
                'cart' => $cart,
                'productsWithKeyId' => $productsWithKeyId,
            ]
        ];
    }

    public function addProducts()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location :/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidAddProduct($_POST);

            if (empty($errors)) {

                $userId = $_SESSION['id'];
                $productId = $_POST['product_id'];

                Cart::addProducts($userId, $productId);

                header('Location :/main');
            } else {

            }

        }

    }


    private function isValidAddProduct(array $data): array
    {
        $errors = [];
        if (!isset($data['product_id'])) {
            $errors['product_id'] = 'product_id is required';
        } else {
            $productId = $data['product_id'];
            if (empty($productId)) {
                $errors['product_id'] = 'product_id не может быть пустым';
            }
        }
        return $errors;
    }







}