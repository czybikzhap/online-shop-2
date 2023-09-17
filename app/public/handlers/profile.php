<?php

session_start();

if (!isset($_SESSION['id'])) {
    header('Location :/login');
}

$userId = $_SESSION['id'];

$pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');
$users = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$users->execute(['id' => $userId]);
$users = $users->fetchAll(PDO::FETCH_ASSOC);


require_once "./html/profile.phtml";