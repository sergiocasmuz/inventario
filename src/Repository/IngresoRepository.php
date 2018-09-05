<?php

namespace App\Repository;

use App\Entity\stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method stock[]    findAll()
 * @method stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, stock::class);
    }

//    /**
//     * @return stock[] Returns an array of stock objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?stock
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
