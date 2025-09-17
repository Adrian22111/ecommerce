<?php

namespace App\Service;

use App\Entity\Product;
use App\Dto\UploadResult;
use App\Entity\ProductImage;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductImageService
{
    public function __construct(
        private FileUploader $fileUploader,
        private EntityManagerInterface $entityManager,
        private string $productDirectory
    ) {}

    public function addImageToProduct(Product $product, UploadedFile $uploadedFile): UploadResult
    {
        $uploadResult = $this->fileUploader->upload(
            $uploadedFile,
            $this->productDirectory
        );

        if ($uploadResult->isSuccess()) {
            $productImage = new ProductImage();
            $productImage->setName($uploadResult->getFileName());
            $product->addProductImage($productImage);

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }

        return $uploadResult;
    }
}
