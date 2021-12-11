<?php

namespace App\Repository;

use App\Entity\AvatarFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvatarFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvatarFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvatarFile[]    findAll()
 * @method AvatarFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatarFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvatarFile::class);
    }

    // /**
    //  * @return AvatarFile[] Returns an array of AvatarFile objects
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
    public function findOneBySomeField($value): ?AvatarFile
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
