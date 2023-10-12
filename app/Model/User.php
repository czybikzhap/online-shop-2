<?php

//namespace App\Model;
//
//use PDO;
//
//class User
//{
//    private int $id;
//    private string $name;
//    private string $email;
//    private string $hash;
//
//
//    public function __construct(string $name, string $email, string $hash)
//    {
//        $this->name  = $name;
//        $this->email = $email;
//        $this->hash  = $hash;
//    }
//
//    public function createUser(): void
//    {
//        $stmt = ConnectFactory::connectDB()->prepare("INSERT INTO users (name, email, password)
//            VALUES (:name, :email, :password)");
//        $stmt->execute(['name' => $this->name, 'email' => $this->email, 'password' => $this->hash]);
//    }
//
//    public static function getByEmail(string $email): self|null
//    {
//        $stmt = ConnectFactory::connectDB()->prepare("SELECT * FROM users WHERE email = :email ");
//        $stmt->execute(['email' => $email]);
//        $data = $stmt->fetch();
//
//        return User::hydrateAll($data);
//
//    }
//
//    public static function getById(int $userId): User|null
//    {
//        $user = ConnectFactory::connectDB()->prepare("SELECT * FROM users WHERE id = :id");
//        $user->execute(['id' => $userId]);
//        $data = $user->fetch(PDO::FETCH_ASSOC);
//
//        return User::hydrateAll($data);
//    }
//
//    private static function hydrateAll($data): User|null
//    {
//        if(empty($data)) {
//            return null;
//        }
//
//        $user = new self($data['name'], $data['email'], $data['password']);
//        $user->setId($data['id']);
//
//        return $user;
//    }
//
//    public function getTotalCost(): int
//    {
//        $cartItems = $this->cartItems();
//
//        $productsWithKeyId = Product::getProductsByUserId($this->id);
//
//        $totalCost = 0;
//        foreach ($cartItems as $elem) {
//            $productId = $elem->getProductId();
//            $product   = $productsWithKeyId[$productId];
//
//            $price  = $product->getPrice();
//            $amount = $elem->getAmount();
//
//            $totalCost = $totalCost + $price * $amount;
//
//        }
//        return $totalCost;
//
//    }
//
//    public function cartItems(): array|null
//    {
//        return CartItem::getAllByUserId($this->id);
//    }
//
//    /**
//     * @param int $id
//     */
//    public function setId(int $id): void
//    {
//        $this->id = $id;
//    }
//    /**
//     * @return int
//     */
//    public function getId(): int
//    {
//        return $this->id;
//    }
//
//    /**
//     * @return string
//     */
//    public function getName(): string
//    {
//        return $this->name;
//    }
//
//    /**
//     * @return string
//     */
//    public function getEmail(): string
//    {
//        return $this->email;
//    }
//
//    /**
//     * @return string
//     */
//    public function getHash(): string
//    {
//        return $this->hash;
//    }
//
//
//}
