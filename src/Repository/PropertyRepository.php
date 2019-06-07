<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    

    /** 
     * @return Query
     */
    public function findAllVisibleQuery(PropertySearch $search)
    {
        $query = $this->findVisibleQuery();

        if($search->getMaxPrice())
        {
            $query = $query
                ->andWhere('p.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        if($search->getMinSurface())
        {
            $query = $query
                ->andWhere('p.surface < :minSurface')
                ->setParameter('minSurface', $search->getMinSurface());
        }

        //si la taille de mon tab getOptions est > 0 ça veut dire qu'on a des options
        if($search->getOptions()->count() > 0)
        {
            $key = 0;
            foreach ($search->getOptions() as $option) {
                $key++;
                $query = $query
                    ->andWhere(":option MEMBER OF p.options")
                    ->setParameter("option", $option);
            }
        }

        return $query->getQuery();//récupère la requête
    }

    /** 
     * @return Property[]
     */
    public function findLatest()
    {
        return $this->findVisibleQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    //fonction créée pour éviter les doublons et mise à la fin car c'est une private
    private function findVisibleQuery()
    {
        return $this->createQueryBuilder('p')//créer un objet pour concevoir la requête en lui donnant un alias
            ->where('p.sold = false');//on récupère tous les biens qui sont pas vendus
    }
    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
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
    public function findOneBySomeField($value): ?Property
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
