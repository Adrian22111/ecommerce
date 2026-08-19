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

    private function getStorage(): CartStorageInterface
    {
        if ($this->security->getUser()) {
            return $this->databaseCartStorage;
        }

        return $this->sessionCartStorage;
    }

    public function addToCart(int $productId, int $quantity)
    {
        dump(123);
        
        $cartItems = $this->getCartItems();
        $currentQuantity = $cartItems[$productId] ?? 0;

        $this->getStorage()->setQuantity($productId, $currentQuantity + $quantity);
    }

    public function removeFromCart()
    {
        $this->getStorage()->removeItem();
    }

    public function getCartItems(): array
    {
        return $this->getStorage()->getCartItems();
    }

    public function getCartItemsCount(): int
    {
        return $this->getStorage()->getCartItemsCount();
    }
}
