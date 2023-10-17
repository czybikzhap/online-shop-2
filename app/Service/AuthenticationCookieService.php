<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class AuthenticationCookieService implements AuthenticateServiceInterface
{
    protected User $user;

    public function getUser(): User|null
    {
        if (isset($this->user)) {
            return $this->user;
        }

        if (!isset($_COOKIE['user_id'])) {
            return null;
        }

        $userRepository = new UserRepository;
        $user = $userRepository->getById($_COOKIE['user_id']);


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

            setcookie('user', $user->getId(), time() + 3600);

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