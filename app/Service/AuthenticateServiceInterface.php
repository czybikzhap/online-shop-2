<?php

namespace App\Service;


interface AuthenticateServiceInterface
{
    public function getUser();
    public function authenticate(string $email, string $pwd);
    public function logout();

}