<?php

namespace App\Repository;

use App\Entity\BillingAddresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillingAddresse>
 *
 * @method BillingAddresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillingAddresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillingAddresse[]    findAll()
 * @method BillingAddresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingAddresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingAddresse::class);
    }

//    /**
//     * @return BillingAddresse[] Returns an array of BillingAddresse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BillingAddresse
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
