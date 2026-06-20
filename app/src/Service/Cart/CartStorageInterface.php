<?php

namespace App\Service\Cart;

interface CartStorageInterface
{
    public function removeItem(int $productId);

    public function getCartItems();

    public function setQuantity(int $productId, int $quantity);

    public function clear();
}
