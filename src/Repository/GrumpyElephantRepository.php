<?php

namespace App\Repository;

use App\Entity\GrumpyElephant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GrumpyElephant|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrumpyElephant|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrumpyElephant[]    findAll()
 * @method GrumpyElephant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrumpyElephantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrumpyElephant::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(GrumpyElephant $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(GrumpyElephant $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return GrumpyElephant[] Returns an array of GrumpyElephant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GrumpyElephant
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
