<?php

namespace App\Model;

use PDO;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $hash;


    public function __construct(string $name, string $email, string $hash)
    {

        $this->name  = $name;
        $this->email = $email;
        $this->hash  = $hash;
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
        $name     = $data['name'];
        $email    = $data['email'];
        $password = $data['password'];

        $user = new self($name, $email, $password);
        $user->setId($data['id']);

        return $user;

    }

    public static function getById(int $userId): User|null
    {
        $user = ConnectFactory::connectDB()->prepare("SELECT * FROM users WHERE id = :id");
        $user->execute(['id' => $userId]);
        $data = $user->fetch(PDO::FETCH_ASSOC);

        $name     = $data['name'];
        $email    = $data['email'];
        $password = $data['password'];

        $obj = new self($name, $email, $password);
        $obj->setId($data['id']);

        return $obj;

    }

    public function getTotalCost(): int
    {
        $cartItems = $this->cartItems();

        $productsWithKeyId = Product::getProductsByUserId($this->id);

        $totalCost = 0;
        foreach ($cartItems as $elem) {
            $productId = $elem->getProductId();
            $product   = $productsWithKeyId[$productId];

            $price  = $product->getPrice();
            $amount = $elem->getAmount();

            $totalCost = $totalCost + $price * $amount;

        }
        return $totalCost;

    }

    public function cartItems(): array|null
    {
        return CartItem::getAllByUserId($this->id);
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
