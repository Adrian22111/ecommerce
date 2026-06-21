<?php

namespace App\Service\Cart\Storage;

use App\Service\Cart\CartStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCartStorage implements CartStorageInterface
{
    private const CART_KEY = 'cart';
    private RequestStack $requestStack;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function getCartItems(): array
    {
        return $this->getSession()->get(self::CART_KEY, []);
    }

    public function setQuantity(int $productId, int $quantity): void
    {
        $cartItems = $this->getCartItems();
        $cartItems[$productId] = $quantity;

        $this->getSession()->set(self::CART_KEY, $cartItems);
    }
    public function removeItem(int $productId): void
        {
        $cartItems = $this->getCartItems();
        if(isset($cartItems[$productId])) {
            unset($cartItems[$productId]);
            $this->getSession()->set(self::CART_KEY, $cartItems);
        }
    }

    public function clear(): void
    {
        $this->getSession()->remove(self::CART_KEY);
    }

    public function getCartItemsCount(): int
    {
        $cartItems = $this->getCartItems();
        $count = 0;

        foreach ($cartItems as $productId => $quantity) {
            $count += $quantity;
        }

        return $count;
    }
}
