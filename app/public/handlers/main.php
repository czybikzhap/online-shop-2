<?php

session_start();

if (!isset($_SESSION['id'])) {
    header('Location :/login');
}
print_r($_SESSION['id']);

$pdo = new PDO('pgsql:host=db;dbname=dbname', 'dbuser', 'dbpwd');
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
//print_r($products);

//print_r($_POST);

require_once "./html/main.phtml";