<?php

namespace App\Controller;

use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\AuthenticateServiceInterface;


class UserController
{
    private AuthenticateServiceInterface $authenticateService;

    private UserRepository $userRepository;


    public function __construct(AuthenticateServiceInterface $authenticateService,
                                UserRepository $userRepository)
    {
        $this->authenticateService = $authenticateService;
        $this->userRepository = $userRepository;
    }

    public function signup(): array
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->IsValidSignUp($_POST);

            if (empty($errors)) {

                $name = $_POST['name'];
                $email = $_POST['email'];
                $pwd = $_POST['password'];

                $hash = password_hash($pwd, PASSWORD_DEFAULT);

                $this->userRepository->createUser($name, $email, $hash);

                $user = $this->authenticateService->authenticate($email, $pwd);

                if ($user !== null) {
                    header('Location:./main');
                }
            }
        }
        return [
            'view' => 'signup',
            'data' => [
                'errors' => $errors
            ]
        ];

    }

    private function IsValidSignUp(array $data): array
    {
        $errors = [];

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

            $dbinfo = $this->userRepository->getByEmail($email);

            if (!empty($dbinfo)) {
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

    public function login(): array
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $errors = $this->isValidLogin($_POST);

            if (empty($errors)) {
                $errors = [];

                $email = $_POST['email'];
                $pwd = $_POST['password'];

                $user = $this->authenticateService->authenticate($email, $pwd);

                if ($user !== null) {
                    header('Location:./main');
                } else {
                    $errors['password'] = 'неверное имя пользователя и пароль';
                }

            }
        }

        return [
            'view' => 'login',
            'data' => [
                'errors' => $errors
            ]
        ];

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

    public function profile(): array
    {
        $user = $this->authenticateService->getUser();

        if ($user === null) {
            header('Location:./login');
        }

        $userId = $user->getId();

        $user = $this->userRepository->getById($userId);


        return [
            'view' => 'profile',
            'data' => [
                'user' => $user,
            ]
        ];
    }

    public function logout(): void
    {
        $this->authenticateService->logout();
    }



}



?>