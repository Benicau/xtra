<?php

namespace App\Repository;

use DateTime;
use App\Entity\Invoices;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Invoices>
 *
 * @method Invoices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoices[]    findAll()
 * @method Invoices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoices::class);
    }

    // Custom function to find invoices within a specific date range and optional payment method filter
    public function findByDateRangeAndPayment($startDate, $endDate, $paymentMethod)
    {
    $queryBuilder = $this->createQueryBuilder('i')
        ->andWhere('i.date >= :startDate')
        ->andWhere('i.date <= :endDate')
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate)
        ->orderBy('i.date', 'ASC');

    if ($paymentMethod !== 'all') {
        $queryBuilder->andWhere('i.paymentMethod = :paymentMethod')
            ->setParameter('paymentMethod', $paymentMethod);
    }

    return $queryBuilder->getQuery()->getResult();
    }

    public function findTotalForCurrentYear($currentYear): ?float
    {
        $startDate = new \DateTime("$currentYear-01-01");
        $endDate = new \DateTime("$currentYear-12-31");

        return $this->createQueryBuilder('i')
            ->select('SUM(i.total) as totalForCurrentYear')
            ->andWhere('i.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findTotalForCurrentMonth($currentYear, $currentMonth): ?float
    {
        $startDate = new \DateTime("$currentYear-$currentMonth-01");
        $endDate = new \DateTime("$currentYear-$currentMonth-" . $startDate->format('t')); // t représente le dernier jour du mois

        return $this->createQueryBuilder('i')
            ->select('SUM(i.total) as totalForCurrentMonth')
            ->andWhere('i.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

   
    public function findTotalForCurrentDay(): ?float
{
    

    $today = new \DateTime('now');
    $startDate = new \DateTime($today->format('Y-m-d'));
    $endDate = clone $today;

    $endDate->modify('+1 day');

    
    return $this->createQueryBuilder('i')
        ->select('SUM(i.total) as totalForCurrentDay')
        ->andWhere('i.date BETWEEN :startDate AND :endDate')
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate)
        ->getQuery()
        ->getSingleScalarResult();
}



    public function save(Invoices $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Invoices $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatest(): ?Invoices
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Invoices[] Returns an array of Invoices objects
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

//    public function findOneBySomeField($value): ?Invoices
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
