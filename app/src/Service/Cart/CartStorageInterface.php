<?php

namespace App\Service\Cart;

use App\Dto\CartItemDto;
use App\Entity\Product;

interface CartStorageInterface
{
    public function removeItem(int $productId);

    /**
     * @return CartItemDto[]
     */
    public function getCartItems(): array;

    public function setQuantity(Product $product, int $quantity);

    public function clear();

    public function getCartItemsCount();
}
