<?php

namespace App\Repository;

use App\Entity\Pricecopycolor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pricecopycolor>
 *
 * @method Pricecopycolor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pricecopycolor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pricecopycolor[]    findAll()
 * @method Pricecopycolor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricecopycolorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pricecopycolor::class);
    }

    public function save(Pricecopycolor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pricecopycolor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Pricecopycolor[] Returns an array of Pricecopycolor objects
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

//    public function findOneBySomeField($value): ?Pricecopycolor
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
