<?php

namespace App\Repository;

use App\Entity\NrosIdentificacion;
use App\Entity\stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NrosIdentificacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method NrosIdentificacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method NrosIdentificacion[]    findAll()
 * @method NrosIdentificacion[]    findArt()
 * @method NrosIdentificacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NrosIdentificacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NrosIdentificacion::class);
    }

    public function findArt($buscar)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.nroArticulo = :val')
            ->setParameter('val', $buscar)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?NrosIdentificacion
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
