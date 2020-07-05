<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function findAllWithCategories()
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c' )
            ->addSelect('c')
            ->getQuery();

        return $qb->execute();
    }

    public function findAllWithCategoriesAndActors()
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c' )
            ->addSelect('c')
            ->leftJoin('p.actors', 'a' )
            ->addSelect('a')
            ->getQuery();

        return $qb->execute();
    }
}
