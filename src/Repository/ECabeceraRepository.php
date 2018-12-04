<?php

namespace App\Repository;

use App\Entity\ECabecera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ECabecera|null find($id, $lockMode = null, $lockVersion = null)
 * @method ECabecera|null findOneBy(array $criteria, array $orderBy = null)
 * @method ECabecera[]    findAll()
  * @method ECabecera[]    findMes($desde, $hasta)
 * @method ECabecera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ECabeceraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ECabecera::class);
    }

    /**
     * @return ECabecera[] Returns an array of ECabecera objects
     */

    public function findMes($desde,$hasta)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.fecha >= :desde and e.fecha <= :hasta')
            ->setParameter('desde', $desde)
            ->setParameter('hasta', $hasta)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?ECabecera
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
