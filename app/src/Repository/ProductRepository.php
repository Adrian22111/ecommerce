<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Collection;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    /**
     * @param array $productIds
     * @return mixed
     */
    public function findWithImagesWhereIdIn(array $productIds): array
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->leftJoin('p.productImages', 'i')
            ->addSelect('i')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $productIds)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return array<int, Product>
     */
    public function findAllWithImages(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.productImages', 'i')
            ->addSelect('i')
            ->getQuery()
            ->getResult();
    }

    public function findOneWithImages(int $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.productImages', 'i')
            ->addSelect('i')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
