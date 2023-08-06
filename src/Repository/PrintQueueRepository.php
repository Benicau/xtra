<?php

namespace App\Repository;

use App\Entity\PrintQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrintQueue>
 *
 * @method PrintQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrintQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrintQueue[]    findAll()
 * @method PrintQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrintQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrintQueue::class);
    }

    public function save(PrintQueue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PrintQueue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PrintQueue[] Returns an array of PrintQueue objects
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

//    public function findOneBySomeField($value): ?PrintQueue
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
