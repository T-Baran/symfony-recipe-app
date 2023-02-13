<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ingredient1 = new Ingredient();
        $ingredient1->setName('papryka');
        $ingredient1->setCalories(300);
        $ingredient1->setCarbohydrates(40);
        $ingredient1->setProtein(10);
        $ingredient1->setFiber(25);
        $manager->persist($ingredient1);

        $ingredient2 = new Ingredient();
        $ingredient2->setName('ser');
        $ingredient2->setCalories(500);
        $ingredient2->setCarbohydrates(20);
        $ingredient2->setProtein(50);
        $ingredient2->setFiber(45);
        $manager->persist($ingredient2);

        $ingredient3 = new Ingredient();
        $ingredient3->setName('kukurydza');
        $ingredient3->setCalories(400);
        $ingredient3->setCarbohydrates(25);
        $ingredient3->setProtein(15);
        $ingredient3->setFiber(25);
        $manager->persist($ingredient3);

        $manager->flush();
    }
}
