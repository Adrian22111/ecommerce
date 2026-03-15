<?php

namespace App\Service;

use App\Entity\Product;
use App\Dto\UploadResult;
use App\Entity\ProductImage;
use App\Service\FileHandler;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductImageService
{
    const PRODUCT_IMAGE = 'product/image/';
    public function __construct(
        private FileHandler            $fileHandler,
        private EntityManagerInterface $entityManager,
        private string                 $uploadsPath,
        private TranslatorInterface    $translator,
        private FilterService          $filterService,
    ) {}

    public function addImageToProduct(Product $product, UploadedFile $uploadedFile): UploadResult
    {
        $uploadResult = $this->fileHandler->upload(
            $uploadedFile,
            $this->uploadsPath . '/' . self::PRODUCT_IMAGE
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

                $filePath = $uploadResult->getUploadDirectory() . '/' . $uploadResult->getFileName();
                $this->fileHandler->delete($filePath);
            }
        }

        return $uploadResult;
    }

    public function removeImageFromProduct(Product $product, ProductImage $productImage): bool
    {
        if (!$product->getProductImages()->contains($productImage)) {
            return false;
        }

        $filePath = $this->uploadsPath . '/' . self::PRODUCT_IMAGE . $productImage->getName();
        $this->fileHandler->delete($filePath);
        $product->removeProductImage($productImage);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return true;
    }

    public function getPublicPath(ProductImage|string|null $productImage): ?string
    {
        if($productImage === null){
            return null;
        }
        $filename = $productImage instanceof ProductImage ? $productImage->getName() : $productImage;
        return '/uploads/' . self::PRODUCT_IMAGE . $filename;
    }

    /**
     * @param ProductImage|string|null $productImage - image or image name of a product
     * @param $thumbName - thumbnail name
     * @return string|null - if successful returns thumbnail path else returns null
     */
    public function getThumbnailPath(ProductImage|string|null $productImage, $thumbName): ?string
    {
        if($productImage === null){
            return null;
        }

        return $this->filterService->getUrlOfFilteredImage(
            $this->getPublicPath($productImage),
            $thumbName
        );
    }
}
