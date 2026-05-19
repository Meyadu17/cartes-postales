<?php

namespace App\Repository;

use App\Entity\Departement;
use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ville>
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    public function save(Ville $ville, bool $flush = false): void
    {
        $this->getEntityManager()->persist($ville);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderedByNom(): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDepartementOrderedByNom(Departement $departement): array
    {
        return $this->createQueryBuilder('v')
            ->where('v.departement = :departement')
            ->setParameter('departement', $departement)
            ->orderBy('v.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
