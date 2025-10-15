<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Measurement>
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function findByLocation(Location $location): array
    {
        $qb = $this->createQueryBuilder('m');

        $nowMs = (int) (microtime(true) * 1000);

        $qb->where('m.location = :location')
            ->andWhere('m.dateMs > :now')
            ->setParameter('location', $location)
            ->setParameter('now', $nowMs);

        return $qb->getQuery()->getResult();
    }
}
