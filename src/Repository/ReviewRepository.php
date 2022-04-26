<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Review $entity, bool $flush = true): void
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
    public function remove(Review $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function totalFiveStars($id)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(r.rating)')
            ->from(Review::class, 'r')
            ->leftJoin('r.menu', 'm')
            ->leftJoin('r.user', 'u')
            ->where('m.id = :id')
            ->andWhere('r.rating = :rating')
            ->setParameter('id', $id)
            ->setParameter('rating', 5)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalFourStars($id)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(r.rating)')
            ->from(Review::class, 'r')
            ->leftJoin('r.menu', 'm')
            ->leftJoin('r.user', 'u')
            ->where('m.id = :id')
            ->andWhere('r.rating = :rating')
            ->setParameter('id', $id)
            ->setParameter('rating', 4)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalThreeStars($id)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(r.rating)')
            ->from(Review::class, 'r')
            ->leftJoin('r.menu', 'm')
            ->leftJoin('r.user', 'u')
            ->where('m.id = :id')
            ->andWhere('r.rating = :rating')
            ->setParameter('id', $id)
            ->setParameter('rating', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalTwoStars($id)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(r.rating)')
            ->from(Review::class, 'r')
            ->leftJoin('r.menu', 'm')
            ->leftJoin('r.user', 'u')
            ->where('m.id = :id')
            ->andWhere('r.rating = :rating')
            ->setParameter('id', $id)
            ->setParameter('rating', 2)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalOneStars($id)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(r.rating)')
            ->from(Review::class, 'r')
            ->leftJoin('r.menu', 'm')
            ->leftJoin('r.user', 'u')
            ->where('m.id = :id')
            ->andWhere('r.rating = :rating')
            ->setParameter('id', $id)
            ->setParameter('rating', 1)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalStars($id)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(r.rating)')
            ->from(Review::class, 'r')
            ->leftJoin('r.menu', 'm')
            ->leftJoin('r.user', 'u')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
