<?php

namespace App\Repository;

use App\Entity\MachineOutil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MachineOutil>
 *
 * @method MachineOutil|null find($id, $lockMode = null, $lockVersion = null)
 * @method MachineOutil|null findOneBy(array $criteria, array $orderBy = null)
 * @method MachineOutil[]    findAll()
 * @method MachineOutil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MachineOutilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MachineOutil::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MachineOutil $entity, bool $flush = true): void
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
    public function remove(MachineOutil $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function getUserMachines($id_user){

    }

    // /**
    //  * @return MachineOutil[] Returns an array of MachineOutil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MachineOutil
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
