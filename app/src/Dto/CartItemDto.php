<?php

namespace App\Dto;

use App\Entity\Product;

class CartItemDto
{
    public function __construct(
        public int $productId,
        public string $name,
        public int $quantity,
        public int $price,
    )
    {}
}
