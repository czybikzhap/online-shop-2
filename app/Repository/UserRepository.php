<?php

use App\Model\CartItem;
use App\Model\ConnectFactory;
use App\Model\Product;
use App\Model\User;

class UserRepository
{
    public function createUser(): array|false
    {
        $stmt = ConnectFactory::connectDB()->prepare("INSERT INTO users (name, email, password) 
            VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $this->name, 'email' => $this->email, 'password' => $this->hash]);

        return $stmt->fetch();
    }

    public static function getByEmail(string $email): User|null
    {
        $stmt = ConnectFactory::connectDB()->prepare("SELECT * FROM users WHERE email = :email ");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        return UserRepository::hydrateAll($data);

    }

    public static function getById(int $userId): User|null
    {
        $user = ConnectFactory::connectDB()->prepare("SELECT * FROM users WHERE id = :id");
        $user->execute(['id' => $userId]);
        $data = $user->fetch(PDO::FETCH_ASSOC);

        return User::hydrateAll($data);
    }

    private static function hydrateAll($data): User|null
    {
        if(empty($data)) {
            return null;
        }

        $user = new User($data['name'], $data['email'], $data['password']);
        $user->setId($data['id']);

        return $user;
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

}