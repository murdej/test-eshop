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
    public function getByFilter(int $categoryId, array $filter, int $limitFrom, int $limitCount) : array
    {
        /*note
         * Místo
         *      SELECT FROM product JOIN product_product_value JOIN product_product_value GROUP BY
         * by šlo použít
         *      SELECT FROM product WHERE EXIST(SELECT FROM product_product_value JOIN product_product_value) AND
         *
         */
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('product_param_value', 'ppv')
            ->innerJoin('param_value', 'pv');

        foreach ($filter as $paramTypeId => $filterValue) {
            if (isset($filterValue['min']) || isset($filterValue['max'])) {
                // min/max - filter by range
                if (isset($filterValue['min']))
                    $qb->where('pv.number_value >= :min' . $paramTypeId)
                        ->setParameter('min' . $paramTypeId, $filterValue['min']);
                if (isset($filterValue['max']))
                    $qb->where('pv.number_value >= :max' . $paramTypeId)
                        ->setParameter('min' . $paramTypeId, $filterValue['min']);
            } else if (is_array($filterValue)) {
                // array - multiple value ids
                if (count($filterValue))
                    $qb->where('ppv.number_value IN :ids' . $paramTypeId)
                        ->setParameter('ids' . $paramTypeId, $filterValue['min']);
            }
        }

        $qb->groupBy('p.id')
            ->orderBy('p.num_order', 'ASC')
            ->setMaxResults($limitCount)
            ->setFirstResult($limitFrom);

        return $qb->getQuery()->getResult();
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
