<?php
namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * RateRepository
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function findRate($name)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('r.price')->where('r.name = :name')->setParameter('name', $name);
        $rate = $qb->getQuery()->getOneOrNullResult();
        if (isset($rate)) {
            $rate = $rate['price'];
        }

        return $rate;
    }
}