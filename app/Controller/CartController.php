<?php

namespace App\Controller;

use App\Entity\CartItem;
use app\Entity\Product;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use App\Service\AuthenticateService;

class CartController
{
    private AuthenticateService $authenticateService;

    public function __construct()
    {
        $this->authenticateService = new AuthenticateService();
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
            $productsInCart = ProductRepository::getProductsByUserId($userId);
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

                $userId = $_SESSION['id'];
                $productId = $_POST['product_id'];

                CartItemRepository::addProduct($userId, $productId);

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

            CartItemRepository::deleteByUserId ($_SESSION['id']);
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

            CartItemRepository::deleteProduct($_SESSION['id'], $_POST['product_id']);

            header('Location: /cart');

        }
    }

}