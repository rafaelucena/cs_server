<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findByParams(array $params)
    {
        $hasMoreThan = $params['has_more_than'] ?? null;
        $hasStock = $params['has_stock'] ?? null;

        $queryBuilder = $this->createQueryBuilder('i');

        if ($hasMoreThan !== null) {
            $queryBuilder->andWhere('i.amount > :value')
                         ->setParameter('value', $hasMoreThan);
        } elseif ($hasStock !== null) {
            if ($hasStock === 'true') {
                $queryBuilder->andWhere('i.amount > :value')
                             ->setParameter('value', 0);
            } else {
                $queryBuilder->andWhere('i.amount = :value')
                             ->setParameter('value', 0);
            }
        }

        return $queryBuilder->getQuery()
                            ->getResult();
    }
}