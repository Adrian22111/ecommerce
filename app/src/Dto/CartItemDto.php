<?php

namespace App\Dto;

class CartItemDto
{
    public function __construct(
        public int $productId,
        public int $quantity,
    )
    {}
}
