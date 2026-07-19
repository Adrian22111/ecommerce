<?php

namespace App\Service\Cart\Storage;

use App\Dto\CartItemDto;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Service\Cart\CartStorageInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DatabaseCartStorage implements CartStorageInterface
{
    public function __construct(
        private CartRepository $cartRepository,
        private Security $security
    )
    {
    }

    public function removeItem(int $productId)
    {
        // TODO: Implement removeItem() method.
    }

    public function getCartItems(): array
    {
        $cartItems = [];
        $user = $this->security->getUser();
        $cart = $this->cartRepository->findUserCartWithItems($user);

        if(!$cart) {
            return [];
        }

        return array_map(fn (CartItem $item) => new CartItemDto(
            $item->getProduct()->getId(),
            $item->getQuantity()
        ), $cart->getCartItems()->toArray());
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
        $cartItems = $this->getCartItems();
        $count = 0;

        foreach ($cartItems as $productId => $quantity) {
            $count += $quantity;
        }

        return $count;
        // TODO: Implement getCartItemsCount() method.
    }
}
