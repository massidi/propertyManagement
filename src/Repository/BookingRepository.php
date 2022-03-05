<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Query\ResultSetMapping;

use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
     *  */

    public function getCurrentBooking()
    {
//        $rsm = new ResultSetMapping();
//
//        $entityManager = $this->getEntityManager();
//
//        $query = $entityManager->createNativeQuery(
//            'SELECT *
//            FROM App\Entity\Booking
//       ', $rsm
//        );
//
//        // returns an array of Product objects
//        return $query->getResult();


        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb = $em->createQueryBuilder();
//        $de= new \DateTime('this year');
//        $de->format('Y-m-d Y-m-d H:i:s');
//        $today = date('Y-m-d Y-m-d H:i:s');

        $query = $em->createQuery('SELECT b, a, c,f 
        FROM App\Entity\Booking b                           
        JOIN b.appartement a 
        JOIN b.clients c
        JOIN b.facturation f
        WHERE b.checkInAt  <= b.checkOutAt OR b.checkOutAt <= b.checkInAt
        
        ORDER BY f.createdAd DESC
        
        
        
        ');


        $rs_reservations = $query->getResult();


        try {

            return $rs_reservations;

        } catch (\Doctrine\ORM\NoResultException $e)
        {

            return null;

        }
    }



    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
