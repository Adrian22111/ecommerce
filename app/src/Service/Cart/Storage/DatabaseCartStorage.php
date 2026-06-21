<?php

namespace App\Service\Cart\Storage;

use App\Service\Cart\CartStorageInterface;

class DatabaseCartStorage implements CartStorageInterface
{

    public function removeItem(int $productId)
    {
        // TODO: Implement removeItem() method.
    }

    public function getCartItems()
    {
        // TODO: Implement getCartItems() method.
    }

    public function setQuantity(int $productId, int $quantity)
    {
        // TODO: Implement setQuantity() method.
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function getCartItemsCount()
    {
        // TODO: Implement getCartItemsCount() method.
    }
}
