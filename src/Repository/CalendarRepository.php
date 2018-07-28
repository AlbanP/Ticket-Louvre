<?php

namespace App\Repository;

use App\Entity\Calendar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    public function showDays()
    {
        $today= new \DateTime();
        $end = new \DateTime();
        $end->add(new \DateInterval('P6M'));
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.day BETWEEN :start AND :end')
        ->setParameter('start', $today->format('Y-m-d')) 
        ->setParameter('end',  $end->format('Y-m-d'))
        ->orderBy('c.day', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }
}