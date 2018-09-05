<?php

namespace App\Repository;

use App\Entity\ILineas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ILineas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ILineas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ILineas[]    findAll()
 * @method ILineas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ILineasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ILineas::class);
    }

//    /**
//     * @return ILineas[] Returns an array of ILineas objects
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
    public function findOneBySomeField($value): ?ILineas
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
