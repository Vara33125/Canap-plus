<?php
namespace App\Service\Basket;

use App\Entity\Product;

    class BasketItem
    {
        public function __construct( public Product $product , public int $quantity)
        {

        }

        public function getAmount()
        {
           return $this->product->getPrice() * $this->quantity;
        }
    }