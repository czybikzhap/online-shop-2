<?php

namespace App\Controller;

use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use App\Service\AuthenticateService;

class CartController
{
    private AuthenticationService $authenticateService;

    public function __construct()
    {
        $this->authenticateService = AuthenticationService();
    }


    public function cart(): array
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        $cartItems = $user->cartItems();
        $userId = $user->getId();

        if (empty($cartItems)) {
            return [
                'view' => 'cart',
                'data' => [
                    'cart' => $cartItems,
                ]
            ];
        } else {
            $productsInCart = new ProductRepository;
            $productsInCart = $productsInCart->getProductsByUserId($userId);
            $totalCost = $user->getTotalCost();

            return [
                'view' => 'cart',
                'data' => [
                    'cartItems'      => $cartItems,
                    'productsInCart' => $productsInCart,
                    'totalCost'      => $totalCost,
                ]
            ];
        }

    }

    public function addProduct(): void
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidAddProduct($_POST);

            if (empty($errors)) {

                $userId = $user->getId();
                $productId = $_POST['product_id'];

                $addProduct = new CartItemRepository;
                $addProduct->addProduct($userId, $productId);


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
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $deleteCart = new CartItemRepository();
            $deleteCart->deleteByUserId ($user->getId());
        }
        header('Location: /main');
    }

    public function deleteProduct(): void
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $deleteProduct = new CartItemRepository();
            $deleteProduct->deleteProduct($user->getId(), $_POST['product_id']);

            header('Location: /cart');

        }
    }

}