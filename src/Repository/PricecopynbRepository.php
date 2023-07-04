<?php

namespace App\Repository;

use App\Entity\Pricecopynb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pricecopynb>
 *
 * @method Pricecopynb|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pricecopynb|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pricecopynb[]    findAll()
 * @method Pricecopynb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricecopynbRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pricecopynb::class);
    }

    public function save(Pricecopynb $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pricecopynb $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Pricecopynb[] Returns an array of Pricecopynb objects
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

//    public function findOneBySomeField($value): ?Pricecopynb
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
