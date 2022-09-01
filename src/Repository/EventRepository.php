<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findAllByDate()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM App\Entity\Event e ORDER BY e.date ASC')
            ->getResult()
            ;
    }

    public function findEventByMonth(Array $date) {

        ['month' => $month, 'year' => $year] = $date;

        $allEvents = $this->getEntityManager()
            ->createQuery('SELECT e FROM App\Entity\Event e ORDER BY e.date ASC')
            ->getResult()
            ;
        $events = array_filter($allEvents, function($el) use ($year, $month) {
           return $el->getDate()->format('Y') === $year && (int)$el->getDate()->format('m') === (int)$month;
        });

        return [...$events];
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
