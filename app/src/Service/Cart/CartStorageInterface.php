<?php

namespace App\Service\Cart;

use App\Dto\CartItemDto;

interface CartStorageInterface
{
    public function removeItem(int $productId);

    /**
     * @return CartItemDto[]
     */
    public function getCartItems(): array;

    public function setQuantity(int $productId, int $quantity);

    public function clear();

    public function getCartItemsCount();
}
