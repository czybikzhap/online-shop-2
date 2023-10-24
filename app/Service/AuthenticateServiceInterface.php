<?php

namespace App\Service;
use App\Entity\User;

interface AuthenticateServiceInterface
{
    public function getUser(): User|null;
    public function authenticate(string $email, string $pwd): User|null;
    public function logout(): void;

}