<?php

namespace App\Twig\Components;

use App\Entity\ProductImage;
use App\Repository\ProductRepository;
use App\Service\ProductImageService;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'twig_components/product_list.html.twig')]
class ProductList
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductImageService $productImageService,
    )
    {

    }

    public function getProducts(): array
    {
        return $this->productRepository->findAllWithImages();
    }

    public function imageUrl(ProductImage $productImage): ?string
    {
        $thumbPath = $this->productImageService->getThumbnailPath($productImage, 'product_list_thumbnail_small');
        if($thumbPath)
        {
            return $thumbPath;
        }
        else
        {
            return '/images/placeholders/no_image_placeholder.jpg';
        }
    }
}
