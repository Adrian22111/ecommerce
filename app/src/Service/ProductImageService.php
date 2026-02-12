<?php

namespace App\Service;

use App\Entity\Product;
use App\Dto\UploadResult;
use App\Entity\ProductImage;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductImageService
{
    public function __construct(
        private FileUploader $fileUploader,
        private EntityManagerInterface $entityManager,
        private string $productDirectory,
        private TranslatorInterface $translator,
        private KernelInterface $kernel
    ) {}

    public function addImageToProduct(Product $product, UploadedFile $uploadedFile): UploadResult
    {
        $uploadResult = $this->fileUploader->upload(
            $uploadedFile,
            $this->productDirectory
        );

        if ($uploadResult->isSuccess()) {
            try{
                $productImage = new ProductImage();
                $productImage->setName($uploadResult->getFileName());
                $product->addProductImage($productImage);

                $this->entityManager->persist($product);
                $this->entityManager->flush();
                $uploadResult->setDatabaseId($productImage->getId());
            } catch (\Exception $exception){
                $uploadResult->setSuccess(false);
                $uploadResult->setMessage($this->translator->trans('image_upload_unsuccessful', [], 'admin.product'));

                //TODO rebuild FileUploader INTO FileService and add methods to get filePath and remove files
                $filePath = $uploadResult->getUploadDirectory() . '/' . $uploadResult->getFileName();
                if (file_exists($filePath)) {
                    $res = unlink($filePath);
                }
            }
        }

        return $uploadResult;
    }

    public function removeImageFromProduct(Product $product, ProductImage $productImage): bool
    {
        if (!$product->getProductImages()->contains($productImage)) {
            return false;
        }

        $filePath = $this->kernel->getProjectDir() . '/public' . $this->productDirectory . '/' . $productImage->getName();
        if (file_exists($filePath)) {
            $res = unlink($filePath);
        }

        $product->removeProductImage($productImage);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return true;
    }
}
