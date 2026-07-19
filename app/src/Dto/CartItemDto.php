<?php

namespace App\Dto;

class CartItemDto
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity
    )
    {}
}
