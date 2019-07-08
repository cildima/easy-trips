<?php

namespace App\Repository;

use App\Entity\CountryTranslations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CountryTranslations|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountryTranslations|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountryTranslations[]    findAll()
 * @method CountryTranslations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryTranslationsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CountryTranslations::class);
    }

//    /**
//     * @return CountryTranslations[] Returns an array of CountryTranslations objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CountryTranslations
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
