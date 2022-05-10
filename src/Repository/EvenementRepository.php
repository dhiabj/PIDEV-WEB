<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenement $entity, bool $flush = true): void
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
    public function remove(Evenement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function find_Nb_Rec_Par_Status($type)
    {

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT DISTINCT  count(r.id) FROM   App\Entity\Evenement r  where r.categorie = :categorie   '
        );
        $query->setParameter('categorie', $type);
        return $query->getResult();
    }
    public function OrderByName()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Reservation m order by m.nom ASC');
        return $query->getResult();
    }
    public function Filter($categorie)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Evenement m where m.categorie =:categorie')
            ->setParameter('categorie', $categorie);
        return $query->getResult();
    }
    public function select()
    {  $time = new \DateTime() ;
        $time->format('H:i:s \O\n Y-m-d');
        $time1=new \DateTime();
        $time1->add( date_interval_create_from_date_string('7 days'));
        $time1->format('H:i:s \O\n Y-m-d');
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Evenement m where m.date between ?1 and ?2')
            ->setParameter('1',$time)
            ->setParameter('2',$time1);

        return $query->getResult();
    }
   /* public function mise_a_jour()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
           DELETE FROM `evenement` WHERE date < CURRENT_DATE ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }*/
}

