<?php

namespace App\Repository;

use App\Entity\Soldier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Soldier>
 *
 * @method Soldier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Soldier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Soldier[]    findAll()
 * @method Soldier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoldierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soldier::class);
    }

    public function save(Soldier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Soldier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function removeAll(): void
    {
        $q = $this->createQueryBuilder('s');
        $q->delete()->getQuery()->getResult();
    }

//    /**
//     * @return Soldier[] Returns an array of Soldier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Soldier
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
