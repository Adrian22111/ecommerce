<?php

namespace App\Service\Cart\Storage;

use App\Dto\CartItemDto;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\Cart\CartStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DatabaseCartStorage implements CartStorageInterface
{
    public function __construct(
        private CartRepository $cartRepository,
        private Security $security,
        private CartItemRepository $cartItemRepository,
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository,
    )
    {
    }

    public function removeItem(int $productId)
    {
        // TODO: Implement removeItem() method.
    }

    /**
     * @return CartItemDto[]
     *
     */
    public function getCartItems(): array
    {
        $user = $this->security->getUser();
        $cart = $this->cartRepository->findUserCartWithItems($user);

        if(!$cart) {
            return [];
        }

        $result = [];
        foreach($cart->getCartItems() as $cartItem) {
            $result[$cartItem->getProduct()->getId()] = new CartItemDto(
                $cartItem->getProduct()->getId(),
                $cartItem->getQuantity(),
            );
        }

        return $result;
    }

    private function getOrCreateCart(User $user): Cart
    {
        $userCart = $this->cartRepository->getByUser($user);
        if(null === $userCart) {
            $userCart = new Cart();
            $userCart->setUser($user);
            $this->entityManager->persist($userCart);
            $this->entityManager->flush();
        }

        return $userCart;
    }

    private function getOrCreateCartItem(Cart $userCart, int $productId): CartItem
    {
        $cartItem = $this->cartItemRepository->findOneByCartAndProductId($userCart, $productId);
        if(!$cartItem){
            $cartItem = new CartItem();
            $product = $this->productRepository->find($productId);
            $cartItem->setProduct($product);
            $cartItem->setCart($userCart);
        }

        return $cartItem;
    }

    public function setQuantity(int $productId, int $quantity): void
    {
        $userCart = $this->getOrCreateCart($this->security->getUser());
        $cartItem = $this->getOrCreateCartItem($userCart, $productId);
        $cartItem->setQuantity($quantity);

        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function getCartItemsCount(): int
    {
        $cartItems = $this->getCartItems();
        $count = 0;

        foreach ($cartItems as  $cartItem) {
            $count += $cartItem->quantity;
        }

        return $count;
    }
}
