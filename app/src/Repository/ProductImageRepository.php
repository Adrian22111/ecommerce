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
            return 0 ;
        }

        $cases = $params = [];

        foreach ($imagesOrder as $imageId => $position) {
            $cases[] = 'WHEN id = ? THEN ?';
            $params[] = $imageId;
            $params[] = $position + 1;
        }
        $params[] = $productId;

        $sql = "
            UPDATE product_image
            SET position =
                CASE %s
                ELSE position
                END
            WHERE
                product_id = ?
        ";

        $sql = sprintf($sql, implode(' ', $cases));

       return $this->getEntityManager()
            ->getConnection()
            ->executeStatement($sql, $params);
    }
}
