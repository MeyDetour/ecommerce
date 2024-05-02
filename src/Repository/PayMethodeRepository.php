<?php

namespace App\Repository;

use App\Entity\PayMethode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PayMethode>
 *
 * @method PayMethode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayMethode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayMethode[]    findAll()
 * @method PayMethode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayMethodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayMethode::class);
    }

    //    /**
    //     * @return PayMethode[] Returns an array of PayMethode objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PayMethode
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
