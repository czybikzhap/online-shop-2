<?php

namespace App\Entity;


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