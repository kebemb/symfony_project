<?php

namespace App\Repository;

use App\Entity\Bien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bien>
 *
 * @method Bien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bien[]    findAll()
 * @method Bien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bien::class);
    }

   /**
    * @return Bien[] Returns an array of Bien objects
    */
    public function listeBienDispoApresDate(string $date): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.dateDispo >= :date')
            ->setParameter('date', $date)
            ->orderBy('b.nom')
            //->setMaxResults()
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Bien
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
