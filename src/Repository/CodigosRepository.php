<?php

namespace App\Repository;

use App\Entity\Codigos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Codigos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Codigos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Codigos[]    findAll()
 * @method Codigos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodigosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Codigos::class);
    }

//    /**
//     * @return Codigos[] Returns an array of Codigos objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Codigos
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
