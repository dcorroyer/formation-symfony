<?php

namespace App\Cart;

use App\Entity\Product;

class CartItem
{
    /**
     * @var Product
     */
    public $product;
    /**
     * @var int
     */
    public $quantity;

    /**
     * CartItem constructor.
     *
     * @param Product $product
     * @param int $quantity
     */
    public function __construct(Product $product, int $quantity)
    {
        $this->product  = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->quantity;
    }
}