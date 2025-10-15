<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }
    public function findOneByCityAndOptionalCountry(string $city, ?string $country): ?Location
    {
        $qb = $this->createQueryBuilder('l')
            ->andWhere('LOWER(l.city) = :city')
            ->setParameter('city', mb_strtolower($city));

        if ($country !== null && $country !== '') {
            $qb->andWhere('UPPER(l.country) = :country')
                ->setParameter('country', mb_strtoupper($country));
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
