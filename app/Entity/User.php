<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;

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

    public function getTotalCost(): int
    {
        $cartItems = $this->cartItems();

        $productsWithKeyId = new ProductRepository();
        $productsWithKeyId = $productsWithKeyId->getProductsByUserId($this->id);

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
        $cartItems = new CartItemRepository;
        $cartItems = $cartItems->getAllByUserId($this->id);
        return $cartItems;
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