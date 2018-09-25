<?php

namespace App\Repository;

use App\Entity\ILineas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ILineas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ILineas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ILineas[]    findAll()
 * @method ILineas[]    findLineas($idArt,$orden)
 * @method ILineas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ILineasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ILineas::class);
    }

    public function findLineas($idArt, $orden)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.idArticulo = :idArt and i.orden = :orden')
            ->setParameter('idArt', $idArt)
            ->setParameter('orden', $orden)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



}
