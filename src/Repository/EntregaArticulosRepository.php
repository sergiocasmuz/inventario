<?php

namespace App\Repository;

use App\Entity\EntregaArticulos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntregaArticulos|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntregaArticulos|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntregaArticulos[]    findAll()
 * @method EntregaArticulos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntregaArticulosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntregaArticulos::class);
    }

//    /**
//     * @return EntregaArticulos[] Returns an array of EntregaArticulos objects
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
    public function findOneBySomeField($value): ?EntregaArticulos
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
