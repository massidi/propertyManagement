<?php

namespace App\Repository;

use App\Entity\Appartement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Appartement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appartement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appartement[]    findAll()
 * @method Appartement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appartement::class);
    }

    // /**
    //  * @return Appartement[] Returns an array of Appartement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Appartement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function checkAppartementAvailability($appartement_id, $date_start, $date_final)
    {
        $em = $this->getEntityManager();


        $sd = $em->createQuery("
            SELECT COUNT(b.appartement) FROM App\Entity\Booking b
                WHERE NOT (b.checkOutAt <= '$date_start'
                   OR
                   b.checkInAt >= '$date_final')
                AND b.appartement = $appartement_id");


//         dd($sd);
        try {
            return $sd->getSingleScalarResult();
        }
        catch (NoResultException | NonUniqueResultException $e)
        {
            return null;
        }


    }

    public function getAvailableRooms($date_start, $date_final)
    {
        $em = $this->getEntityManager();


        $qb = $em->createQueryBuilder();

        $nots = $em->createQuery("
        SELECT IDENTITY(b.appartement) FROM App\Entity\Booking b
            WHERE NOT (b.checkOutAt   < $date_start
               OR
               b.checkInAt > $date_final)
        ");

        $dql_query = $nots->getDQL();
        $qb->resetDQLParts();


        $query = $qb->select('a')
            ->from('App\Entity\Appartement', 'a')
            ->where($qb->expr()->notIn('a.id', $dql_query))
            ->getQuery()
            ->getResult();

        try {

            return $query;
        } catch (NoResultException $e) {
            return null;
        }
    }
}
