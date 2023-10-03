<?php

namespace App\Model;

use App\Model\ConnectFactory;
use PDO;

class User
{
    private string $name;
    private string $email;
    private string $hash;


    public function __construct(string $name, string $email, string $hash)
    {

        $this->name = $name;
        $this->email = $email;
        $this->hash = $hash;
    }

    public function createUser(): array|false
    {
        $stmt = ConnectFactory::connectDB()->prepare("INSERT INTO users (name, email, password) 
            VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $this->name, 'email' => $this->email, 'password' => $this->hash]);

        return $stmt->fetch();
    }

    public static function getByEmail(string $email): self|null
    {
        $stmt = ConnectFactory::connectDB()->prepare("SELECT * FROM users WHERE email = :email ");
        $stmt->execute(['email' => $email]);

        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        $user = new self($name, $email, $password);
        $user->setId($data['id']);

        return $user;

    }

    public static function getById(string $userId): array
    {
        $user = ConnectFactory::connectDB()->prepare("SELECT * FROM users WHERE id = :id");
        $user->execute(['id' => $userId]);
        return $user->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }


}
