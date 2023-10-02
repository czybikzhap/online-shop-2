<?php

namespace App\Model;

use App\Model\ConnectFactory;

use PDO;

class Cart
{

    private int $userId;
    private int $productId;

    private PDO $pdo;

    public function __construct(int $userId, $productId)
    {
        $this->userId = $userId;
        $this->productId = $productId;

        $this->pdo = ConnectFactory::connectDB();
    }

    public static function getCart (int $userId): array|null
    {
        $stmt = ConnectFactory::connectDB()->prepare("SELECT * FROM cart 
         WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $data;
    }

    public static function getProductsInCart (int $userId): array
    {
        $cart = ConnectFactory::connectDB()->prepare("SELECT * FROM cart WHERE user_id = :user_id");
        $cart->execute(['user_id' => $userId]);

        return $cart->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function productsWithKeyId(array $cart): array
    {
        $productIds = [];
        foreach ($cart as $productInCart) {
            $productIds[] = $productInCart['product_id'];
        }
        //print_r($productIds);

        $productIds = implode(', ', $productIds);

        $stmt = ConnectFactory::connectDB()->query(
            "SELECT * FROM products WHERE id in ($productIds)");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //print_r($products);

        $productsWithKeyId = [];
        foreach($products as $product) {
            $productsWithKeyId[$product['id']] = $product;
        }
        return $productsWithKeyId;
    }

    public static function addProduct($userId, $productId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare("INSERT INTO cart (user_id, product_id, amount) 
                VALUES (:user_id, :product_id, 1)
    ON CONFLICT (user_id, product_id) DO UPDATE SET amount = cart.amount + EXCLUDED.amount");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

       // return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public static function delete (int $userId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare('DELETE FROM cart 
            WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
    }

    public static function deleteProduct($userId, $productId): void
    {
        $stmt = ConnectFactory::connectDB()->prepare('DELETE FROM cart
       WHERE user_id = :user_id AND product_id = :product_id');
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }


}