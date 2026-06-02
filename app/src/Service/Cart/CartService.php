<?php

namespace App\Service\Cart;

use App\Service\Cart\Storage\DatabaseCartStorage;
use App\Service\Cart\Storage\SessionCartStorage;
use Symfony\Bundle\SecurityBundle\Security;

class CartService
{
    public function __construct(
        private Security $security,
        private DatabaseCartStorage $databaseCartStorage,
        private SessionCartStorage $sessionCartStorage
    ){}

    private function getStorage()
    {
        if ($this->security->getUser()) {
            return $this->databaseCartStorage;
        }

        return $this->sessionCartStorage;
    }

    public function addToCart($productId)
    {
        $this->getStorage()->addItem($productId);
    }

    public function removeFromCart()
    {
        $this->getStorage()->removeItem();
    }

    public function getCartItems(): array
    {
        return $this->getStorage()->getCartItems();
    }

    public function updateQuantity()
    {
        $this->getStorage()->updateQuantity();
    }
}
