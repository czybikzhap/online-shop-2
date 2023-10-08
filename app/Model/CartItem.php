<?php

namespace App\Model;

use App\Model\ConnectFactory;
use PDO;

class CartItem
{
    private int $userId;
    private int $productId;
    private int $amount;

    public function __construct(int $userId, $productId, $amount)
    {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->amount = $amount;
    }
    public static function addProduct(int $userId, int $productId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare("
                INSERT INTO cart_items (user_id, product_id, amount) 
                VALUES (:user_id, :product_id, 1)
                ON CONFLICT (user_id, product_id)   
                DO UPDATE SET amount = cart_items.amount + EXCLUDED.amount
            ");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public static function getAllByUserId(int $userId): array|null
    {
        $stmt = ConnectFactory::connectDB()->prepare("SELECT * FROM cart_items 
         WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return self::hydrateAll($data);
    }

    public static function getProductsInCart (int $userId): array|null
    {
        $cart = ConnectFactory::connectDB()->prepare("SELECT * FROM cart_items WHERE user_id = :user_id");
        $cart->execute(['user_id' => $userId]);
        $data = $cart->fetchAll(PDO::FETCH_ASSOC);

        return self::hydrateAll($data);
    }

    public static function hydrateAll(array $data): array|null
    {
        if (empty($data)) {
            return null;
        }
        $cartItems = [];
        foreach ($data as $cartItemArr) {
            $cartItems[] = new self($cartItemArr['user_id'],
                                    $cartItemArr['product_id'],
                                    $cartItemArr['amount']);
        }

        return $cartItems;
    }



    public static function deleteByUserId (int $userId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare('
            DELETE FROM cart_items 
            WHERE user_id = :user_id'
        );
        $stmt->execute(['user_id' => $userId]);
    }

    public static function deleteProduct($userId, $productId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare('
            DELETE FROM cart_items
            WHERE user_id = :user_id 
            AND product_id = :product_id'
        );
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

}