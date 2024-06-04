<?php

namespace App\Repository;

use App\Entity\ParamValue;
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

    /**
     * @param array<int,mixed[]|array{min:int|float|null,max:int|float|null}> $filter
     * @return Product[] Returns an array of Product objects
     */
    public function getByFilter(?int $categoryId, array $filter, ?float $minPrice, ?float $maxPrice, int $limitFrom, int $limitCount) : array
    {
        /*note
         * Místo
         *      SELECT FROM product JOIN product_product_value JOIN product_product_value GROUP BY
         * by šlo použít
         *      SELECT FROM product WHERE EXIST(SELECT FROM product_product_value JOIN product_product_value) AND
         *
         */
        $qb = $this->createQueryBuilder('p');

        foreach ($filter as $paramTypeId => $filterValue) {
            $pva = 'pv' . $paramTypeId;
            $qb->innerJoin('p.paramValues', $pva, 'WITH', $pva . '.paramType = :paramTypeId' . $paramTypeId);
            $qb->setParameter('paramTypeId' . $paramTypeId, $paramTypeId);

            if (isset($filterValue['min']) || isset($filterValue['max'])) {
                // min/max - filter by range
                if (isset($filterValue['min']))
                    $qb->andWhere($pva . '.numberValue >= :min' . $paramTypeId)
                        ->setParameter('min' . $paramTypeId, $filterValue['min']);
                if (isset($filterValue['max']))
                    $qb->andWhere($pva . '.numberValue <= :max' . $paramTypeId)
                        ->setParameter('max' . $paramTypeId, $filterValue['max']);
            } else if (is_array($filterValue)) {
                // array - multiple value ids
                if (count($filterValue))
                    $qb->andWhere($pva . '.id IN (:ids' . $paramTypeId . ')')
                        ->setParameter('ids' . $paramTypeId, $filterValue);
            }
        }

        if ($categoryId !== null) 
            $qb->andWhere('p.category = :categoryId')
                ->setParameter('categoryId', $categoryId);

        if ($minPrice !== null)
            $qb->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);

        if ($maxPrice !== null)
            $qb->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);

        $qb->groupBy('p.id')
            ->orderBy('p.numOrder', 'ASC')
            ->setMaxResults($limitCount)
            ->setFirstResult($limitFrom);

        $query = $qb->getQuery();

        return $query->getResult();
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
