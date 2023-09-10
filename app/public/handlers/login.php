<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $errors = isValidLogin($_POST);

    if (empty($errors)) {
        $errors = [];

        $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');

        $email = $_POST['email'];
        $pwd = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email ");
        $stmt->execute(['email' => $email]);
        $dbinfo = $stmt->fetch();
        //print_r($dbinfo);


        if (!empty($dbinfo['email']) && password_verify($pwd, $dbinfo['password'])) {
            session_start();
            $_SESSION['id'] = $dbinfo['id'];

            header('Location:./main');
        } else {
            $errors['password'] = 'неверное имя пользователя и пароль';
        }

    }
}

require_once "./html/login.phtml";

function isValidLogin(array $data): array
{
    $errors = [];
    if (!isset($data['email'])) {
        $errors['email'] = "поле email не задано";
    } else {
        $email = $data['email'];
        if (empty($email)) {
            $errors['email'] = 'введите email';
        }
    }
    if (!isset($data['password'])){
        $errors['password'] = 'поле пароля не задано';
    } else {
        $password = $data['password'];
        if (empty($password)) {
            $errors['password'] = 'введите пароль';
        }
    }
    return $errors;
}

