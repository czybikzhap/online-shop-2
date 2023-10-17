<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class AuthenticationCookieService implements AuthenticateService
{
    protected User $user;

    public function getUser(): User|null
    {
        if (isset($this->user)) {
            return $this->user;
        }

        if (!isset($_COOKIE['id'])) {
            return null;
        }

        $user = new UserRepository;
        $user = $user->getById($_COOKIE['id']);
        setcookie('user', $user->getEmail(), time() + 3600);

        return $this->user = $user;
    }

    public function authenticate(string $email, string $pwd): User|null
    {
        $userRepository = new UserRepository;
        $user = $userRepository->getByEmail($email);

        if ($user === null) {
            return null;
        }

        if (password_verify($pwd, $user->getHash())) {

            $_COOKIE['id'] = $user->getId();
            setcookie('user', $user->getEmail(), time() + 3600);

            return $this->user = $user;
        }

        return null;
    }


    public function logout(): void
    {
        unset($this->user);
        setcookie('user', null, time() - 3600);
    }

}