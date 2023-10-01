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
        //print_r($productsWithKeyId);die;

        return [
            'view' => 'cart',
            'data' => [
                'cart' => $cart,
                'productsWithKeyId' => $productsWithKeyId,
            ]
        ];
    }

    public function addProducts(): void
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: /login');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            //header('Location: /main');
            //print_r($_POST['product_id']);die;

            $errors = $this->isValidAddProduct($_POST);

            if (empty($errors)) {
                // print_r($_POST['product_id']);die;

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

    public function delete(): void
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: /login');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            Cart::delete($_SESSION['id']);
        }
    }

    public function deleteProduct(): void
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: /login');
       } //else {
//            header('Location: /cart');
//        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            print_r($_POST);
            Cart::deleteProduct($_SESSION['id'], $_POST['product_id']);

        }
    }







}