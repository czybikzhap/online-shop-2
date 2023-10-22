<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class AuthenticationSessionService implements AuthenticateServiceInterface
{
    protected User $user;

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser(): User|null
    {
        if (isset($this->user)) {
            return $this->user;
        }
        session_start();

        if (!isset($_SESSION['id'])) {
            return null;
        }

        $user = $this->userRepository->getById($_SESSION['id']);

        return $this->user = $user;
    }

    public function authenticate(string $email, string $pwd): User|null
    {
        $user = $this->userRepository->getByEmail($email);

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