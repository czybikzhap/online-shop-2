<?php

namespace App\Controller;

use App\Model\Cart;
use App\Model\Product;

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

        if (empty($cart)) {
            $errors= ' ';
        } else {
            $productsWithKeyId = Product::productsWithKeyId($cart);
            $totalCost = $this->totalCost();
        }

        if (empty($cart)) {
        return [
            'view' => 'cart',
            'data' => [
                'cart' => $cart,
                'errors' => $errors,
            ]
        ];
        } else {
            return [
                'view' => 'cart',
                'data' => [
                    'cart' => $cart,
                    'productsWithKeyId' => $productsWithKeyId,
                    'totalCost' => $totalCost,
                ]
            ];
        }

    }

    public function addProducts(): void
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: /login');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidAddProduct($_POST);

            if (empty($errors)) {

                $userId = $_SESSION['id'];
                $productId = $_POST['product_id'];

                Cart::addProduct($userId, $productId);

                header('Location: /main');

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

    private function totalCost(): int
    {
        $userId = $_SESSION['id'];

        $cart = Cart::getProductsInCart($userId);
        $productsWithKeyId = Product::productsWithKeyId($cart);

        $idPrice = [];
        foreach ($productsWithKeyId as $elem) {
            $idPrice[$elem['id']] = $elem['price'];
        }

        $idAmount = [];
        foreach ($cart as $elem) {
            $idAmount[$elem['product_id']] = $elem['amount'];
        }

        $total = [];
        foreach ($idAmount as $key1 => $value1) {
            foreach ($idPrice as $key2 => $value2) {
                if($key1 === $key2) {
                    $total[$key1] = $value2 * $value1;
                }
            }
        }

        return array_sum($total);

    }

    public function delete(): void
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: /login');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            Cart::delete($_SESSION['id']);
        }
        header('Location: /main');
    }

    public function deleteProduct(): void
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: /login');
       }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            Cart::deleteProduct($_SESSION['id'], $_POST['product_id']);

            header('Location: /cart');

        }
    }







}