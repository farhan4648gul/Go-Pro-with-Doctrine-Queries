<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\FortuneCookie;
use App\Model\CategoryFortuneStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
use Doctrine\DBAL\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FortuneCookie>
 *
 * @method FortuneCookie|null find($id, $lockMode = null, $lockVersion = null)
 * @method FortuneCookie|null findOneBy(array $criteria, array $orderBy = null)
 * @method FortuneCookie[]    findAll()
 * @method FortuneCookie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FortuneCookieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FortuneCookie::class);
    }

    public function save(FortuneCookie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    } 

    public function rawQuery(){
        $conn = $this->getEntityManager()->getConnection(); 
        $sql = 'SELECT * FROM fortune_cookie'; 

        $conn->prepare($sql);
        
        $stmt = $conn->executeQuery($sql);
        $result = $stmt->fetchAllAssociative();  

        // dd ($result); 

        return $result; 

        // OR 

        // $stmt = $conn->prepare('SELECT * FROM fortune_cookie WHERE id = :id');
        // $stmt->bindValue('id', 1, \PDO::PARAM_INT); 
        // $stmt->execute();
        // $result = $stmt->fetchAllAssociative(); 
        // dd ($result); 

        // OR
        // using a DTO 
        // $result = $stmt->fetchAssociative(); 

        //OR 
        // return new CategoryFortuneStats(...$result->fetchAssociative()); 


    }

    /**
     * @param QueryBuilder $queryBuilder 
     * @return QueryBuilder 
     */
    public function customQueryWithJoin(QueryBuilder $queryBuilder): QueryBuilder{
        return $queryBuilder->addSelect('fortuneCookie')
                            ; // ->leftJoin('category.fortuneCookie', 'fortuneCookie'); 


    }

    public function customQueryJoiner(QueryBuilder $qb): QueryBuilder{
    
        return $this->customQueryWithJoin($qb)
                    ->$this->addOrderBy($qb) 
                    ->groupBy('category.id'); 

    } 

    private function addOrderBy(QueryBuilder $qb = null ) : QueryBuilder { 
        return ($qb ?? $this->createQueryBuilder('fortuneCookie'))
                    ->orderBy('category.name', Order::Ascending); 
    }



    public function countNumberPrintedForCategory (Category $category) {

        $result = $this->createQueryBuilder('fortuneCookie') 
                        ->select('SUM(fortuneCookie.numberPrinted) as totalPrinted') 
                        ->addSelect('category.name') 
                        ->addSelect('AVG(fortuneCookie.numberPrinted) as averagePrinted') 
                        ->innerJoin('fortuneCookie.category', 'category') 
                        ->andWhere('category.id = :categoryId')
                        ->setParameter('categoryId', $category->getId()) 
                        ->getQuery() 
                        ->getSingleResult();
        

        return $result; 


    }

    public function remove(FortuneCookie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FortuneCookie[] Returns an array of FortuneCookie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FortuneCookie
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
