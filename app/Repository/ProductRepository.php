<?php

namespace App\Repository;

use App\Entity\Product;
use PDO;


class ProductRepository
{
    public static function getAll(): array|null
    {

        $stmt = ConnectFactory::connectDB()->query("SELECT * FROM products");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $productsWithKeyId = [];
        foreach($data as $product) {
            $productsWithKeyId[$product['id']] = $product;
        }

        return ProductRepository::hydrateAll($productsWithKeyId);
    }

    public static function getById(int $id): array|null
    {
        $stmt = ConnectFactory::connectDB()->query(
            "SELECT * FROM products WHERE id in ($id)");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ProductRepository::hydrateAll($products);
    }


    public static function getProductsByUserId(int $userId): array|null
    {
        $stmt = ConnectFactory::connectDB()->query(
            "SELECT products.*
                    FROM products 
                    INNER JOIN cart_items on cart_items.product_id = products.id
                    WHERE cart_items.user_id = $userId");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ProductRepository::hydrateAll($products);
    }

    public static function hydrateAll(array $products): array|null
    {

        if (empty($products)) {
            return null;
        }

        $object = [];
        foreach($products as $product) {
            $object[$product['id']] = new Product(
                $product['id'],
                $product['product_name'],
                $product['price'],
                $product['description'],
                $product['image url']
            );
        }

        return $object;
    }

}