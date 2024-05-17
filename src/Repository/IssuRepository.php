<?php

namespace App\Repository;

use App\Entity\Issu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Issu>
 *
 * @method Issu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issu[]    findAll()
 * @method Issu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Issu::class);
    }

    //    /**
    //     * @return Issu[] Returns an array of Issu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Issu
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
