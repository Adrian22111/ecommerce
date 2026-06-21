<?php

namespace App\Twig\Components;

use App\Service\Cart\CartService;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'twig_components/cart_badge.html.twig')]
class CartBadge
{
    public function __construct(
        private CartService $cartService,
    )
    {

    }

    public function getCartItemsCount()
    {
        return $this->cartService->getCartItemsCount();
    }
}
