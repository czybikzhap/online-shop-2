<?php

namespace App\Service;
use App\Entity\User;

interface AuthenticateServiceInterface
{
    public function getUser(): User;
    public function authenticate(string $email, string $pwd);
    public function logout();

}