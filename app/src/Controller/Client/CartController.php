<?php

namespace App\Controller\Client;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use App\Service\ProductImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private ProductRepository $productRepository
    )
    {

    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        $cartItemDtos  = $this->cartService->getCartItems();
//        $productIds = [];
//        foreach ($cartItemDtos as $cartItemDto) {
//           $productIds[] = $cartItemDto->product->getId();
//        }
//        $products = $this->productRepository->findWithImagesWhereIdIn($productIds);

        return $this->render('client/cart/index.html.twig', [
            'cartItemDtos' => $cartItemDtos,
        ]);
    }

    #[Route('/cart/add/{product}', name: 'add_to_cart', methods: ['GET'])]
    public function addToCart(
        Product $product,
        Request $request,
        CartService $cartService
    ): Response
    {
        $quantity = $request->query->get('quantity', 1);
        if($quantity <= 0 ) {
            return new JsonResponse([],Response::HTTP_BAD_REQUEST);
        }

        try{
            $cartService->addToCart($product, $quantity);
            $countItems = $cartService->getCartItemsCount();
        } catch (\Throwable $exception) {
            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['countItems' => $countItems], Response::HTTP_OK);
    }
}
