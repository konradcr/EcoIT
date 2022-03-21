<?php

namespace App\Repository;

use App\Entity\StudyMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudyMaterial|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyMaterial|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyMaterial[]    findAll()
 * @method StudyMaterial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyMaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyMaterial::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(StudyMaterial $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(StudyMaterial $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return StudyMaterial[] Returns an array of StudyMaterial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudyMaterial
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
