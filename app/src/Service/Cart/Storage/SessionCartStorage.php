<?php

namespace App\Service\Cart\Storage;

use App\Dto\CartItemDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Cart\CartStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCartStorage implements CartStorageInterface
{
    private const CART_KEY = 'cart';
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository,
    )
    {

    }

    public function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function getCartItems(): array
    {
        $sessionCartItems = $this->getSession()->get(self::CART_KEY, []);
        $productIds = array_keys($sessionCartItems);
        $products = $this->productRepository->findWhereIdIn($productIds);

        $cartItemDtos = [];
        foreach ($sessionCartItems as $productId => $quantity) {
            $product = $products[$productId] ?? null;
            if($product) {
                $cartItemDtos[$productId] = new CartItemDto(
                    $product->getId(),
                    $product->getName(),
                    $quantity,
                    $product->getPrice()
                );
            }
        }

        return $cartItemDtos;
    }

    public function setQuantity(Product $product, int $quantity): void
    {
        $sessionCartItems = $this->getSession()->get(self::CART_KEY, []);
        $sessionCartItems[$product->getId()] = $quantity;

        $this->getSession()->set(self::CART_KEY, $sessionCartItems);
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

        foreach ($cartItems as $cartItem) {
            $count += $cartItem->quantity;
        }

        return $count;
    }
}
