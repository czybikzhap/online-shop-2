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


}