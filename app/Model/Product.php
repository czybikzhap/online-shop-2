<?php

namespace App\Model;

use PDO;

class Product
{
    private int $id;
    private string $productName;
    private string $price;
    private string $description;
    private string $imageUrl;

    public function __construct(int $id, string $productName, string $price, string $description, string $imageUrl)
    {
        $this->id = $id;
        $this->productName = $productName;
        $this->price = $price;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
    }

    public static function getAll(): array|null
    {

        $stmt = ConnectFactory::connectDB()->query("SELECT * FROM products");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $productsWithKeyId = [];
        foreach($data as $product) {
            $productsWithKeyId[$product['id']] = $product;
        }

        return Product::hydrateAll($productsWithKeyId);
    }

    public static function getById(int $id): array|null
    {
        $stmt = ConnectFactory::connectDB()->query(
            "SELECT * FROM products WHERE id in ($id)");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Product::hydrateAll($products);
    }


    public static function getProductsByUserId(int $userId): array|null
    {
        $stmt = ConnectFactory::connectDB()->query(
            "SELECT products.*
                    FROM products 
                    INNER JOIN cart_items on cart_items.product_id = products.id
                    WHERE cart_items.user_id = $userId");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Product::hydrateAll($products);
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
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }


}