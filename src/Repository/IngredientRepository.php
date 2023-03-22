<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function save(Ingredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveWithFlush(Ingredient $entity):void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function onlyFlush():void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Ingredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByNameAndLimit(string $value, int $results): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.name LIKE :val')
            ->setParameter('val','%' . $value . '%')
            ->orderBy('i.id', 'ASC')
            ->setMaxResults($results)
            ->getQuery()
            ->getResult()
        ;
    }
}
