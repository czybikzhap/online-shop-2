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
        $cart = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
        $cart->execute(['user_id' => $userId]);
        $cart = $cart->fetchAll(PDO::FETCH_ASSOC);
//print_r($cart);


//$productId = $cart['product_id'];
//
//$price = $pdo->query("SELECT * FROM products WHERE product_id = :product_id");
//$price->execute(['product_id' => $productId]);
//$price = $price->fetchAll(PDO::FETCH_ASSOC);
//print_r($price);


        require_once "./../Views/cart.phtml";
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