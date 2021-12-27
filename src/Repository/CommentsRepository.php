<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    // /**
    //  * @return Comments[] Returns an array of Comments objects
    //  */
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
    public function findOneBySomeField($value): ?Comments
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Returns all Comments per page
     * @return void 
     */
    public function getPaginateComments($page, $limit, $id){
        $query = $this->createQueryBuilder('a')
            ->where('a.isActiveCom = 1')
            ->andWhere('a.user = :id')
            ->setParameter(':id', $id)
            ->orderBy('a.dateCreateCom', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Comments per page
     * @return void 
     */
    public function getTotalComments($id){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isActiveCom = 1')
            ->andWhere('a.user = :id')
            ->setParameter(':id', $id)
        ;
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns all Comments per page and user
     * @return void 
     */
    public function getPaginateCommentsAdmin($page, $limit){
        $query = $this->createQueryBuilder('a')
            ->where('a.isActiveCom = 1')
            ->orderBy('a.dateCreateCom', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Count all Comments per page and user
     * @return void 
     */
    public function getTotalCommentsAdmin(){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isActiveCom = 1')
        ;
        return $query->getQuery()->getSingleScalarResult();
    }
}
