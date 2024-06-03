<?php

namespace App\Repository;

use App\Entity\ParamType;
use App\Entity\ParamValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParamValue>
 */
class ParamValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParamValue::class);
    }

    public function findOrCreate(int $paramTypeId, string|int|float $value): ParamValue
    {
        //$item
            $q= $this->createQueryBuilder('pv')
            ->select('pv')
            ->andWhere('pv.paramType = :paramTypeId')
            ->andWhere(':value IN (pv.numberValue, pv.stringValue)')
            ->setParameter('paramTypeId', $paramTypeId)
            ->setParameter('value', $value)
            ->getQuery();
        $item = $q->getOneOrNullResult();
        if (!$item) {
            $item = new ParamValue();
            $item->setParamType($this->getEntityManager()->getReference(ParamType::class, $paramTypeId));
            if (is_string($value))
                $item->setStringValue($value);
            else
                $item->setNumberValue($value);
            $this->getEntityManager()->persist($item);
            $this->getEntityManager()->flush();
        }

        return $item;
    }

    //    /**
    //     * @return ParamValue[] Returns an array of ParamValue objects
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

    //    public function findOneBySomeField($value): ?ParamValue
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    /**
     * @return ParamValue[] Returns an array of ParamValue objects
     */
    public function findByProductId(int $productId, bool $withParamType = false): array
    {
        $q = $this->createQueryBuilder('pv')
            ->join('product_param_value', 'ppv')
            ->andWhere('ppv.product_id = :productId')
            ->setParameter('productId', $productId);
        if ($withParamType) $q->join('pv.paramType', 'pt');

        return $q->getQuery()
            ->getResult();
    }
}
