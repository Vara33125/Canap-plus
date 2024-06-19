<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findProductSearch(string $keywords) : array
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't');
    
        if (!empty($keywords)) {
            $query = $query
                ->andWhere('LOWER(p.name) LIKE :keywords OR LOWER(c.name) LIKE :keywords OR LOWER(t.name) LIKE :keywords')
                ->setParameter('keywords', '%' . mb_strtolower($keywords) . '%');
        }
    
        return $query->getQuery()->getResult();
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
}
