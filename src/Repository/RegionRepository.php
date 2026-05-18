<?php

namespace App\Repository;

use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Region>
 */
class RegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    public function save(Region $region, bool $flush = false): void
    {
        $this->getEntityManager()->persist($region);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderedByNom(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByCode(string $code): ?Region
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.departements', 'd')
            ->addSelect('d')
            ->andWhere('r.code = :code')
            ->setParameter('code', $code)
            ->orderBy('d.code', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
