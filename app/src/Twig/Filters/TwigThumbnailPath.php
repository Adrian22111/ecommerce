<?php

namespace App\Twig\Filters;

use App\Entity\ProductImage;
use App\Service\ProductImageService;
use Twig\Attribute\AsTwigFunction;

class TwigThumbnailPath
{
    public function __construct(
        private ProductImageService $productImageService,
    )
    {
    }

    #[AsTwigFunction('thumbnailPath')]
    public function thumbnailPath(ProductImage|string|null $productImage, string $thumbName)
    {
        return $this->productImageService->getThumbnailPath($productImage, $thumbName);
    }
}
