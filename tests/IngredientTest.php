<?php

namespace App\Tests;

use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngredientTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function test_ingredient_can_be_created_in_the_database()
    {
        $ingredient = new Ingredient();
        $ingredient->setName('cebula');
        $ingredient->setCalories(400);
        $ingredient->setCarbohydrates(40);
        $ingredient->setFiber(50);
        $ingredient->setProtein(60);
        $ingredient->setFat(20);

        $this->entityManager->persist($ingredient);
        $this->entityManager->flush($ingredient);

        $ingredientRepository = $this->entityManager->getRepository(Ingredient::class);

        $ingredientRecord = $ingredientRepository->findOneBy(['name' => 'cebula']);
        $this->assertEquals('cebula', $ingredientRecord->getName());
        $this->assertEquals(400, $ingredientRecord->getCalories());
        $this->assertEquals(40, $ingredientRecord->getCarbohydrates());
        $this->assertEquals(50, $ingredientRecord->getFiber());
        $this->assertEquals(60, $ingredientRecord->getProtein());
        $this->assertEquals(20, $ingredientRecord->getFat());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}