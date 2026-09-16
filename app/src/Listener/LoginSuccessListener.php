<?php

namespace App\Listener;

use App\Service\Cart\Storage\DatabaseCartStorage;
use App\Service\Cart\Storage\SessionCartStorage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class, method: 'mergeCarts')]
class LoginSuccessListener
{
    private DatabaseCartStorage $databaseCartStorage;
    private SessionCartStorage $sessionCartStorage;

    public function __construct(
        DatabaseCartStorage $databaseCartStorage,
        SessionCartStorage $sessionCartStorage
    )
    {
        $this->databaseCartStorage = $databaseCartStorage;
        $this->sessionCartStorage = $sessionCartStorage;
    }

    public function mergeCarts(LoginSuccessEvent $loginSuccessEvent): void
    {
        $cartItemsSession = $this->sessionCartStorage->getCartItems() ?? [];
        $cartItemsDatabase = $this->databaseCartStorage->getCartItems() ?? [];

        foreach($cartItemsSession as $productId => $sessionProduct) {
            $dbProduct = $cartItemsDatabase[$productId] ?? null;
            if($dbProduct) {
                $dbProduct->quantity = $dbProduct->quantity + $sessionProduct->quantity;
            } else {
                $dbProduct = $sessionProduct;
            }
            $mergedCartItems[$productId] = $dbProduct;
        }

        // databaseCartStorage setQuantity może tym? 

        //TODO  mam tablicę z pozycjami zmodyfikowanymi albo nowymi, teraz edytować lub dodać te pozycje do koszyka

        //TODO co jeśli koszyk nie istnieje wcześniej

    }
}
