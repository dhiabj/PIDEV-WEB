<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Entity\MenuCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuCommande[]    findAll()
 * @method MenuCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuCommande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MenuCommande $entity, bool $flush = true): void
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
    public function remove(MenuCommande $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function sumTotal($user)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('sum(m.prix) as total')
            ->from(MenuCommande::class, 'mc')
            ->leftJoin('mc.menu', 'm')
            ->leftJoin('mc.command', 'c')
            ->where('c.etat = :etat')
            ->andWhere('c.user = :user')
            ->setParameter('etat', 'non validé')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findPanier($user)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('mc')
            ->from(MenuCommande::class, 'mc')
            ->leftJoin('mc.menu', 'm')
            ->leftJoin('mc.command', 'c')
            ->where('c.etat = :etat')
            ->andWhere('c.user = :user')
            ->setParameter('etat', 'non validé')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return MenuCommande[] Returns an array of MenuCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuCommande
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
