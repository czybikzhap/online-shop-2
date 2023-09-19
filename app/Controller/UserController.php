<?php

class UserController
{
    public function signup(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->IsValidSignUp($_POST);

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

                header('Location:./main');


            }
        }

        require_once "../Views/signup.html";
    }

    private function IsValidSignUp(array $data): array
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

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidLogin($_POST);

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
        require_once "../Views/login.phtml";

    }

    private function isValidLogin(array $data): array
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
        if (!isset($data['password'])) {
            $errors['password'] = 'поле пароля не задано';
        } else {
            $password = $data['password'];
            if (empty($password)) {
                $errors['password'] = 'введите пароль';
            }
        }
        return $errors;
    }

    public function profile()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            header('Location :/login');
        }

        $userId = $_SESSION['id'];

        $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');
        $users = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $users->execute(['id' => $userId]);
        $users = $users->fetchAll(PDO::FETCH_ASSOC);


        require_once "../Views/profile.phtml";
    }
}



?>