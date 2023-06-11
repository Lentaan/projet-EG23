<?php

namespace App\Repository;

use App\Entity\ZoneControl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ZoneControl>
 *
 * @method ZoneControl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneControl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneControl[]    findAll()
 * @method ZoneControl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneControlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneControl::class);
    }

    public function save(ZoneControl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ZoneControl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ZoneControl[] Returns an array of ZoneControl objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('z')
//            ->andWhere('z.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('z.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ZoneControl
//    {
//        return $this->createQueryBuilder('z')
//            ->andWhere('z.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
