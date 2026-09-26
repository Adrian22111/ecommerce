<?php

namespace App\Service\Cart;

use App\Dto\CartItemDto;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Service\Cart\Storage\DatabaseCartStorage;
use App\Service\Cart\Storage\SessionCartStorage;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;

class CartService
{
    public function __construct(
        private Security $security,
        private DatabaseCartStorage $databaseCartStorage,
        private SessionCartStorage $sessionCartStorage,
        private EntityManagerInterface $entityManager
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
        $cartItems = $this->getCartItems();
        $currentQuantity = $cartItems[$productId]->quantity ?? 0;
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

    public function mergeUserCarts()
    {
        $cartItemsDtoSession = $this->sessionCartStorage->getCartItems() ?? [];

        $user = $this->security->getUser();
        if(!$user instanceof User){
            throw new LogicException('User must be an instance of User class');
        }

        $cart = $this->databaseCartStorage->getOrCreateCart($user);
        $cartItems = $cart->getCartItems();

        $productMap = [];
        foreach($cartItems as $cartItem) {
            $productId = $cartItem->getProduct()->getId();
            $productMap[$productId] = $cartItem;
        }

        /**
         * @var CartItemDto $cartItemDtoSession
         */
        foreach($cartItemsDtoSession as $cartItemDtoSession) {
            $existingCartItem = $productMap[$cartItemDtoSession->productId] ?? null;

            if($existingCartItem) {
                $quantity = $existingCartItem->getQuantity() + $cartItemDtoSession->quantity;
                $existingCartItem->setQuantity($quantity);
            } else {
                $cartItem = new CartItem();
                $cartItem->setQuantity($cartItemDtoSession->quantity);
                $cartItem->setCart($cart);
                $product = $this->entityManager->getReference(
                    Product::class,
                    $cartItemDtoSession->productId
                );
                $cartItem->setProduct($product);
                $productMap[$cartItemDtoSession->productId] = $cartItem;
                $this->entityManager->persist($cartItem);
            }
        }
        $this->sessionCartStorage->clear();
        $this->entityManager->flush();
    }
}
