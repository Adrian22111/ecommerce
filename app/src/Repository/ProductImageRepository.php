<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductImage>
 */
class ProductImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    //    /**
    //     * @return ProductImage[] Returns an array of ProductImage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProductImage
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function updateProductImagesOrder(Product|int $product, array $imagesOrder)
    {
        if($product instanceof Product) {
            $productId = $product->getId();
        } else {
            $productId = $product;
        }

        if (empty($imagesOrder)) {
            return;
        }

        $cases = [];
        $params = [
            'productId' => $productId,
        ];

        foreach ($imagesOrder as $imageId => $position) {
            $cases[] = 'WHEN id = ? THEN ?';
            $params[] = $imageId;
            $params[] = $position + 1;
        }

        $ids = array_keys($imagesOrder);
        $placeholders = implode(', ', array_fill(0, count($ids), '?'));

        $sql = sprintf(
            'UPDATE product_image
         SET position = CASE %s ELSE position END
         WHERE product_id = ? AND id IN (%s)',
            implode(' ', $cases),
            $placeholders
        );

        // Parametry pozycyjne muszą odpowiadać kolejności placeholderów.
        $params = [];

        foreach ($imagesOrder as $imageId => $position) {
            $params[] = $imageId;
            $params[] = $position;
        }

        $params[] = $productId;

        foreach ($ids as $id) {
            $params[] = $id;
        }

        $this->getEntityManager()
            ->getConnection()
            ->executeStatement($sql, $params);
    }

    public function findImagesByProduct(Product|int $product)
    {


    }
}
