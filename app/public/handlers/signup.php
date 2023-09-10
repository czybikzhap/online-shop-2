<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $errors = IsValidSignUp($_POST);

    if (empty($errors)) {
        $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');

        $name = $_POST['name'];
        $email = $_POST['email'];
        $pwd = $_POST['password'];

        $password = password_hash($pwd, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) 
                VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
        //$dbinfo = $stmt->fetch();


    }
}

    require_once "./html/signup.html";

function IsValidSignUp(array $data): array
{
    $errors = [];

    $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');

    if (!isset($data['name'])) {
        $errors['name'] = 'поле имя не задано';
    } else {
        $name = $data['name'];
        if (strlen($name) < 2) {
            $errors['name'] = "имя должно содержать больше 2 символов";
        }
        for ($i = 1; $i <= 9; $i++) {
            if (str_contains($name, $i)) {
                $errors['name'] = "имя не должно содержать цифры";
            }
        }
    }
    if (!isset($data['email'])) {
        $errors['email'] = "поле email не задано";
    } else {
        $email = $data['email'];
        if (strlen($email) < 3) {
            $errors['email'] = "email должен содержать больше 3 символов";
        }
        if (!str_contains($email, '@')) {
            $errors['email'] = "некорректный email";
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email ");
        $stmt->execute(['email' => $email]);
        $userData = $stmt->fetch();
        if (!empty($userData['email'])) {
            $errors['email'] = 'пользователь с таким адресом электронной почты уже зарегистрирован';
        }
    }
    if (!isset($data['password'])) {
        $errors['password'] = "поле пароля не задано";
    } else {
        $pwd = $data['password'];
        if (empty($pwd)) {
            $errors['password'] = "поле пароля не может быть пустым";
        }
        if (strlen($pwd) < 3) {
            $errors['password'] = "пароль должен содержать больше 3 символов";
        }
        if ($pwd !== $_POST['confirm-password']) {
            $errors['password'] = "пароли не совпадают";
        }
    }
    return $errors;
}
