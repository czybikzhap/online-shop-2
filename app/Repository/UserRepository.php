<?php

namespace App\Repository;

use App\Entity\User;

use PDO;

class UserRepository
{
    public function createUser($name, $email, $hash): void
    {
        $stmt = ConnectFactory::connectDB()->prepare("
            INSERT INTO users (name, email, password) 
            VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hash]);
    }

    public function getByEmail(string $email): User|null
    {
        $stmt = ConnectFactory::connectDB()->prepare("    
            SELECT * FROM users 
            WHERE email = :email ");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return UserRepository::hydrate($data);

    }

    public function getById(int $userId): User|null
    {
        $user = ConnectFactory::connectDB()->prepare("
            SELECT * FROM users 
            WHERE id = :id");
        $user->execute(['id' => $userId]);
        $data = $user->fetch(PDO::FETCH_ASSOC);

        return UserRepository::hydrate($data);
    }

    private static function hydrate($data): User|null
    {
        if(empty($data)) {
            return null;
        }

        $user = new User($data['name'], $data['email'], $data['password']);
        $user->setId($data['id']);

        return $user;
    }




}