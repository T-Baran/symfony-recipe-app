<?php

namespace App\Repository;

use App\Entity\RecipeIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecipeIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeIngredient::class);
    }

    public function save(RecipeIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function onlyFlush():void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(RecipeIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMacronutrientsByRecipe($value): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.ingredient', 'i')
            ->andWhere('r.recipe = :val')
            ->setParameter('val', $value)
            ->select('r.unit','r.quantity','i.calories', 'i.carbohydrates', 'i.fiber', 'i.protein', 'i.fat')
            ->getQuery()
            ->getResult()
        ;
    }

    public function CheckIfIngredientCanBeDeleted($id): bool
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.ingredient = :val')
            ->setParameter('val', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()===null
        ;
    }
}
