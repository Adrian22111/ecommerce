<?php

namespace App\Service\Cart;

interface CartStorageInterface
{
    public function addItem();

    public function removeItem();

    public function getCartItems();

    public function updateQuantity();

    public function clear();
}
