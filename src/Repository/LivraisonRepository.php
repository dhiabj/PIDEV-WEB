<?php

namespace App\Repository;

use App\Entity\Livraison;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livraison[]    findAll()
 * @method Livraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livraison::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Livraison $entity, bool $flush = true): void
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
    public function remove(Livraison $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return Livraison[] Returns an array of Livraison objects
      */

    public function findByLivreur($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.livreur = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Livraison[] Returns an array of Livraison objects
     */

    public function findByUser($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNbLiv($liv){

        return $this->getEntityManager()->createQueryBuilder()
            ->select(' count(l.id) ')
            ->from(Livraison::class, 'l')
            ->where('l.etat =:etat Or l.etat =:etat1')
            ->setParameter('etat', 'En Cours')
            ->setParameter('etat1', 'Non Livree')
            ->andWhere('l.livreur = :livreur')
            ->setParameter('livreur', $liv)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Livraison
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
