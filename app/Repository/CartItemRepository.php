<?php

namespace App\Repository;

use App\Entity\CartItem;
use PDO;


class CartItemRepository
{
    public function addProduct(int $userId, int $productId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare("
                INSERT INTO cart_items (user_id, product_id, amount) 
                VALUES (:user_id, :product_id, 1)
                ON CONFLICT (user_id, product_id)   
                DO UPDATE SET amount = cart_items.amount + EXCLUDED.amount
            ");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function getAllByUserId(int $userId): array|null
    {
        $stmt = ConnectFactory::connectDB()->prepare("
            SELECT * FROM cart_items 
            WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return self::hydrateAll($data);
    }


    public static function hydrateAll(array $data): array|null
    {
        if (empty($data)) {
            return null;
        }

        $cartItems = [];
        foreach ($data as $cartItemArr) {
            $cartItems[] = new CartItem($cartItemArr['user_id'],
                $cartItemArr['product_id'],
                $cartItemArr['amount']);
        }

        return $cartItems;
    }



    public function deleteByUserId (int $userId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare('
            DELETE FROM cart_items 
            WHERE user_id = :user_id'
        );
        $stmt->execute(['user_id' => $userId]);
    }

    public function deleteProduct($userId, $productId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare('
            DELETE FROM cart_items
            WHERE user_id = :user_id 
            AND product_id = :product_id'
        );
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

}