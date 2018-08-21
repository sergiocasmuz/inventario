<?php

namespace App\Repository;

use App\Entity\Articulos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Articulos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articulos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articulos[]    findAll()
 * @method Articulos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticulosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Articulos::class);
    }

//    /**
//     * @return Articulos[] Returns an array of Articulos objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articulos
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
