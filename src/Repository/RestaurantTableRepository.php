<?php

namespace App\Repository;

use App\Entity\RestaurantTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantTable[]    findAll()
 * @method RestaurantTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantTable::class);
    }

    public function findTablesByRestaurant($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.restaurant_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findEnabledTablesByRestaurant($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.restaurant_id = :val')
            ->andWhere('r.status_active = 1')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }
}
