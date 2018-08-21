<?php

namespace App\Repository;

use App\Entity\RecepcionArticulos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RecepcionArticulos|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecepcionArticulos|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecepcionArticulos[]    findAll()
 * @method RecepcionArticulos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecepcionArticulosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecepcionArticulos::class);
    }

//    /**
//     * @return RecepcionArticulos[] Returns an array of RecepcionArticulos objects
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
    public function findOneBySomeField($value): ?RecepcionArticulos
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
