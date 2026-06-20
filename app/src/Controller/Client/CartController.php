<?php

namespace App\Controller\Client;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    public function __construct()
    {

    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart/add/{productId}', name: 'add_to_cart', methods: ['GET'])]
    public function addToCart(
        $productId,
        CartService $cartService
    ): Response
    {
        try{
            $cartService->addToCart($productId, 1);
        } catch (\Throwable $exception) {
            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([], Response::HTTP_OK);
    }
}
