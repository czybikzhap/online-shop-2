<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class AuthenticationSessionService implements AuthenticateService
{
    protected User $user;

    public function getUser(): User|null
    {
        if (isset($this->user)) {
            return $this->user;
        }
        session_start();

        if (!isset($_SESSION['id'])) {
            return null;
        }

        $user = new UserRepository;
        $user = $user->getById($_SESSION['id']);

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
            session_start();
            $_SESSION['id'] = $user->getId();

            return $this->user = $user;
        }

        return null;
    }


    public function logout(): void
    {
        session_start();
        session_destroy();

        unset($this->user);
    }

}