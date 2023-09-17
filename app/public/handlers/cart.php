<?php

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




require_once "./html/cart.phtml";