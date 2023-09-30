<?php

namespace App\Model;

use App\Model\ConnectFactory;
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
        //print_r($data);

        $productsWithKeyId = [];
        foreach($data as $product) {
            $productsWithKeyId[$product['id']] = $product;
        }

        $arrayObjects = [];
        foreach ($productsWithKeyId as $product) {
            $arrayObjects[] = new Product(
                $product['id'],
                $product['product_name'],
                $product['price'],
                $product['description'],
                $product['image url']
            );
        }


        if (empty($arrayObjects)) {
            return null;
        }

        return $arrayObjects;
    }

    public static function getById($id): Product|null
    {
        $stmt = ConnectFactory::connectDB()->query(
            "SELECT * FROM products WHERE id in ($id)");
        $product = $stmt->fetch();
        //print_r($product);die;
        if (empty($product)) {
            return null;
        }

        $object = new Product(
            $product['id'],
            $product['product_name'],
            $product['price'],
            $product['description'],
            $product['image url']
        );
//        print_r($object);die;

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