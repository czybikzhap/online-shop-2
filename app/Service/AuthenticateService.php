<?php

namespace App\Service;

use App\Model\User;

class AuthenticateService
{
    private User $user;

    public function getUser(): User|null
    {
        if (isset($this->user)) {
            return $this->user;
        }
        session_start();

        if (!isset($_SESSION['id'])) {
            return null;
        }

        $this->user = User::getById($_SESSION['id']);

        return $this->user;
    }

    public function authenticate(string $email, string $pwd): User|null
    {
        $user = User::getByEmail($email);

        if ($user === null) {
            return null;
        }

        if (password_verify($pwd, $user->getHash())) {
            session_start();
            $_SESSION['id'] = $user->getId();

            $this->user =$user;

            return $this->user;
        }

        return null;
    }



}