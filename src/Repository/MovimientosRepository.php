<?php

namespace App\Repository;

use App\Entity\Movimientos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movimientos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movimientos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movimientos[]    findAll()
 * @method Movimientos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movimientos::class);
    }

//    /**
//     * @return Movimientos[] Returns an array of Movimientos objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movimientos
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
