<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function findByParams(array $params): array
    {
        $hasMoreThan = $params['has_more_than'] ?? null;
        $hasStock = $params['has_stock'] ?? null;

        $queryBuilder = $this->createQueryBuilder('i');

        if ($hasMoreThan !== null) {
            $queryBuilder->andWhere('i.amount > :value')->setParameter('value', $hasMoreThan);
        } elseif ($hasStock !== null) {
            if ($hasStock === 'true') {
                $queryBuilder->andWhere('i.amount > :value')->setParameter('value', 0);
            } else {
                $queryBuilder->andWhere('i.amount = :value')->setParameter('value', 0);
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $name
     *
     * @return Item|null
     */
    public function findByName(string $name, int $id = null): ?Item
    {
        $queryBuilder = $this->createQueryBuilder('i')
                             ->andWhere('i.name = :name')
                             ->setParameter('name', $name);

        if ($id !== null) {
            $queryBuilder->andWhere('i.id != :id')
                         ->setParameter('id', $id);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}