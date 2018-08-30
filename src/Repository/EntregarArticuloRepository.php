<?php

namespace App\Repository;

use App\Entity\EntregarArticulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntregarArticulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntregarArticulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntregarArticulo[]    findAll()
 * @method EntregarArticulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntregarArticuloRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntregarArticulo::class);
    }

//    /**
//     * @return EntregarArticulo[] Returns an array of EntregarArticulo objects
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
    public function findOneBySomeField($value): ?EntregarArticulo
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
