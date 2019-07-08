<?php

namespace App\Repository;

use App\Entity\PageTranslations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PageTranslations|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTranslations|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTranslations[]    findAll()
 * @method PageTranslations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTranslationsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PageTranslations::class);
    }

//    /**
//     * @return PageTranslations[] Returns an array of PageTranslations objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PageTranslations
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
