<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function getAllSorted($sort = 'ASC')
    {
        return $this->createQueryBuilder('c')

            ->orderBy('c.title', $sort)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return array[] Returns an array of Countries with titles as keys and ids as values
     */
    public function findByCountriesAsArrayWithTitlesAsKeys()
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select('c.id, c.title')
            ->addOrderBy('c.title', 'ASC')
            ->addOrderBy('c.id', 'DESC')
        ;
        $countries = $qb->getQuery()->getResult(Query::HYDRATE_SCALAR);

        $temp = [];
        foreach ($countries as $country)
        {
            $temp[$country['title']] = $country['id'];
        }

        $countries = $temp;
        unset($temp);

        return $countries;
    }
}
