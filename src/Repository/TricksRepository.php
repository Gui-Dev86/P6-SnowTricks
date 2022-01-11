<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tricks::class);
    }

    // /**
    //  * @return Tricks[] Returns an array of Tricks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tricks
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Returns all Tricks per page and user
     * @return void 
     */
    public function getPaginateTricks($page, $limit, $id){
        $query = $this->createQueryBuilder('a')
            ->where('a.isActiveTrick = 1')
            ->andWhere('a.user = :id')
            ->setParameter(':id', $id)
            ->orderBy('a.dateUpdateTrick', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Count all Tricks per page and user
     * @return void 
     */
    public function getTotalTricks($id){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isActiveTrick = 1')
            ->andWhere('a.user = :id')
            ->setParameter(':id', $id)
        ;
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns all Tricks per page and user
     * @return void 
     */
    public function getPaginateTricksAdmin($page, $limit){
        $query = $this->createQueryBuilder('a')
            ->where('a.isActiveTrick = 1')
            ->orderBy('a.dateUpdateTrick', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Count all Tricks per page and user
     * @return void 
     */
    public function getTotalTricksAdmin(){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isActiveTrick = 1')
        ;
        return $query->getQuery()->getSingleScalarResult();
    }
}
