<?php

namespace App\Repository;

use App\Entity\Dependencias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dependencias|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dependencias|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dependencias[]    findAll()
 * @method Dependencias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DependenciasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dependencias::class);
    }

//    /**
//     * @return Dependencias[] Returns an array of Dependencias objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dependencias
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
