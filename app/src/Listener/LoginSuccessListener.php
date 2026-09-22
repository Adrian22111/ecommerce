<?php

namespace App\Listener;

use App\Dto\CartItemDto;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Service\Cart\Storage\DatabaseCartStorage;
use App\Service\Cart\Storage\SessionCartStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class, method: 'mergeCarts')]
class LoginSuccessListener
{
    public function __construct(
        private DatabaseCartStorage $databaseCartStorage,
        private SessionCartStorage $sessionCartStorage,
        private Security $security,
        private EntityManagerInterface $entityManager
    )
    {

    }

    public function mergeCarts(LoginSuccessEvent $loginSuccessEvent): void
    {
        $cartItemsDtoSession = $this->sessionCartStorage->getCartItems() ?? [];

        $cart = $this->databaseCartStorage->getOrCreateCart($this->security->getUser());
        $cartItems = $cart->getCartItems();

        /**
         * @var CartItemDto $cartItemDtoSession
         */
        foreach($cartItemsDtoSession as $cartItemDtoSession) {
            $existingCartItem = $cartItems->findFirst(
                fn (int $key, CartItem $item) =>
                    (int) $item->getProduct()->getId() === (int) $cartItemDtoSession->productId
            );

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
                $this->entityManager->persist($cartItem);
            }
        }
        $this->entityManager->flush();
    }
}
