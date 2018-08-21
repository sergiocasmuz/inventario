<?php

namespace App\Repository;

use App\Entity\Recepcion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Recepcion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recepcion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recepcion[]    findAll()
 * @method Recepcion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecepcionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recepcion::class);
    }

//    /**
//     * @return Recepcion[] Returns an array of Recepcion objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recepcion
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
