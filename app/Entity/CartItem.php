<?php

namespace App\Entity;


class CartItem
{
    private int $userId;
    private int $productId;
    private int $amount;

    public function __construct(int $userId, $productId, $amount)
    {
        $this->userId    = $userId;
        $this->productId = $productId;
        $this->amount    = $amount;
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