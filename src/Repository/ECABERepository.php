<?php

namespace App\Repository;

use App\Entity\ECABE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ECABE|null find($id, $lockMode = null, $lockVersion = null)
 * @method ECABE|null findOneBy(array $criteria, array $orderBy = null)
 * @method ECABE[]    findAll()
 * @method ECABE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ECABERepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ECABE::class);
    }

//    /**
//     * @return ECABE[] Returns an array of ECABE objects
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
    public function findOneBySomeField($value): ?ECABE
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
