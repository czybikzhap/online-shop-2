<?php

namespace App\Service;


interface AuthenticateService
{
    public function getUser();
    public function authenticate(string $email, string $pwd);
    public function logout();

}