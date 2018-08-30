<?php

namespace App\Repository;

use App\Entity\ELineas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ELineas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ELineas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ELineas[]    findAll()
 * @method ELineas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ELineasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ELineas::class);
    }

//    /**
//     * @return ELineas[] Returns an array of ELineas objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ELineas
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
