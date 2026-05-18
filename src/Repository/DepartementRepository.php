<?php

namespace App\Repository;

use App\Entity\Departement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Departement>
 */
class DepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departement::class);
    }

    public function save(Departement $departement, bool $flush = false): void
    {
        $this->getEntityManager()->persist($departement);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderedByNom(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedByCode(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function createOrderedByCodeQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.code', 'ASC');
    }
}

