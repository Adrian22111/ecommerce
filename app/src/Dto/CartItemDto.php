<?php

namespace App\Dto;

use App\Entity\Product;

class CartItemDto
{
    public function __construct(
        public Product $product,
        public int $quantity,
    )
    {}
}
