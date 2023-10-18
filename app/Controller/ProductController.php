<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\AuthenticateServiceInterface;

class ProductController
{

    private AuthenticateServiceInterface $authenticateService;

    public function __construct(AuthenticateServiceInterface $authenticateService)
    {
        $this->authenticateService = $authenticateService;
    }

    public function product(): array
    {
        $user = $this->authenticateService->getUser();
        if ($user === null) {
            header("Location: /login");
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidProduct($_POST);
            if (!empty($errors)) {
                print_r($errors);die;
            }

            $id = $_POST['product_id'];

            $productRepository = new ProductRepository;
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