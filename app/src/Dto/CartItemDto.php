<?php

namespace App\Dto;


class CartItemDto
{
    public function __construct(
        public int $productId,
        public string $name,
        public int $quantity,
        public int $price,
        public string $image = '',
    )
    {}
}
