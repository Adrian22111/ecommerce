<?php

namespace App\Listener;

use App\Service\Cart\CartService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class, method: 'mergeCarts')]
class LoginSuccessListener
{
    public function __construct(
        private CartService $cartService,
    )
    {

    }

    public function mergeCarts(): void
    {
        $this->cartService->mergeUserCarts();
    }
}
