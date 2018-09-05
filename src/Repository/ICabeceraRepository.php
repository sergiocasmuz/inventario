<?php

namespace App\Repository;

use App\Entity\ICabecera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ICabecera|null find($id, $lockMode = null, $lockVersion = null)
 * @method ICabecera|null findOneBy(array $criteria, array $orderBy = null)
 * @method ICabecera[]    findAll()
 * @method ICabecera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ICabeceraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ICabecera::class);
    }

//    /**
//     * @return ICabecera[] Returns an array of ICabecera objects
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
    public function findOneBySomeField($value): ?ICabecera
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
