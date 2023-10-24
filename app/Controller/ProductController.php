<?php

namespace App\Controller;

use App\Container;
use App\Repository\ProductRepository;
use App\Service\AuthenticateServiceInterface;

class ProductController
{

    public function product(): array
    {

        $userAuthenticate = Container::get(AuthenticateServiceInterface::class);
        $user = $userAuthenticate->getUser();

        if ($user === null) {
            header("Location: /login");
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidProduct($_POST);
            if (!empty($errors)) {
                print_r($errors);die;
            }

            $id = $_POST['product_id'];

            $productRepository = Container::get(ProductRepository::class);
            $product = $productRepository->getById($id);

        }

        return [
            'view' => 'product',
            'data' => [
                'product' => $product
            ]
        ];
    }

    private function isValidProduct(array $data): array
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