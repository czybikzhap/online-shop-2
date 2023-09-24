<?php

class CartController
{
    public function cart()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            header('Location :/login');
        }

        $userId = $_SESSION['id'];

        $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');
        $cart = $this->getProductsInCart($userId, $pdo);
        //print_r($cart);
        $productsWithKeyId = $this->productsWithKeyId($cart, $pdo);

        require_once "./../Views/cart.phtml";
    }

    private function getProductsInCart (int $userId, PDO $pdo): array
    {
        $cart = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
        $cart->execute(['user_id' => $userId]);

        return $cart->fetchAll(PDO::FETCH_ASSOC);
    }

    private function productsWithKeyId(array $cart, PDO $pdo): array
    {
        $productIds = [];
        foreach ($cart as $productInCart) {
            $productIds[] = $productInCart['product_id'];
        }
        //print_r($productIds);

        $productIds = implode(', ', $productIds);

        $stmt = $pdo->query("SELECT * FROM products WHERE id in ($productIds)");
        $products = $stmt->fetchAll();
//        print_r($products);die;

        $productsWithKeyId = [];
        foreach($products as $product) {
            $productsWithKeyId[$product['id']] = $product;
        }
        return $productsWithKeyId;
    }

    public function addProduct()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location :/login');
        }

        $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');


        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidAddProduct($_POST);

            if (empty($errors)) {

                $userId = $_SESSION['id'];
                $productId = $_POST['product_id'];

                $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, amount) 
                VALUES (:user_id, :product_id, 1)
    ON CONFLICT (user_id, product_id) DO UPDATE SET amount = cart.amount + EXCLUDED.amount");
                $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
                header("Location: /main");
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