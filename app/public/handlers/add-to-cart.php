<?php

session_start();
if (!isset($_SESSION['id'])) {
    header('Location :/login');
}
//print_r($_POST);

$pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');


if($_SERVER['REQUEST_METHOD'] === "POST") {

    $errors = isValidAddProduct($_POST);

    if (empty($errors)) {

        $userId = $_SESSION['id'];
        $productId = $_POST['product_id'];
//    $amount = $_POST['Quantity'];

        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, amount) 
                VALUES (:user_id, :product_id, 1)
    ON CONFLICT (user_id, product_id) DO UPDATE SET amount = cart.amount + EXCLUDED.amount");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        header("Location: /main");
    }

}



function isValidAddProduct(array $data): array
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