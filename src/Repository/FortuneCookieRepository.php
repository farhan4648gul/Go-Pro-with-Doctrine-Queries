<?php

namespace App\Repository;

use App\Entity\Category;
use App\Model\CategoryFortuneStats; 
use App\Entity\FortuneCookie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function countNumberPrintedForCategory (int $id) { 

        $result = $this->createQueryBuilder('fortuneCookie') 
                        ->select (
                            sprintf(
                                'NEW %s(
                                    SUM(fortuneCookie.numberPrinted),  
                                    AVG(fortuneCookie.numberPrinted), 
                                    category.name)',
                                CategoryFortuneStats::class  
                            )
                        )
                        // ->select('SUM(fortuneCookie.numberPrinted) as totalPrinted') 
                        // ->addSelect('category.name') 
                        // ->addSelect('AVG(fortuneCookie.numberPrinted) as averagePrinted') 
                        ->innerJoin('fortuneCookie.category', 'category') 
                        ->andWhere('category.id = :categoryId')
                        ->setParameter('categoryId', $id) 
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
