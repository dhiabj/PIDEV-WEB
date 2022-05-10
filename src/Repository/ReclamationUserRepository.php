<?php

namespace App\Repository;

use App\Entity\ReclamationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReclamationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReclamationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReclamationUser[]    findAll()
 * @method ReclamationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReclamationUser::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ReclamationUser $entity, bool $flush = true): void
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
    public function remove(ReclamationUser $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function noti ($user){
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(u.status)')
            ->from(ReclamationUser::class, 'u')
            ->where('u.user = :user')
            ->setParameter('user', $user)
            ->andWhere('u.status = :status')
            ->setParameter('status', 'unseen')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return ReclamationUser[] Returns an array of ReclamationUser objects
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
    public function findOneBySomeField($value): ?ReclamationUser
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
