<?php

namespace App\Tests;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function test_recipe_and_recipeIngredient_can_be_created_in_the_database()
    {
        $ingredient = new Ingredient();
        $ingredient->setName('ingredient');
        $ingredient->setCalories(400);
        $ingredient->setCarbohydrates(40);
        $ingredient->setFiber(50);
        $ingredient->setProtein(60);

        $recipe = new Recipe();
        $recipe->setName("recipe");
        $recipe->setInstructions(['test', 'test1']);
        $recipe->setServings(5);
        $recipe->setPreparationTime(120);

        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setRecipe($recipe);
        $recipeIngredient->setIngredient($ingredient);
        $recipeIngredient->setQuantity(500);
        $recipeIngredient->setUnit("g");

        $this->entityManager->persist($ingredient);
        $this->entityManager->persist($recipe);
        $this->entityManager->persist($recipeIngredient);
        $this->entityManager->flush();

        $recipeRepository = $this->entityManager->getRepository(Recipe::class);
        $recipeIngredientRepository = $this->entityManager->getRepository(RecipeIngredient::class);
        $ingredientRepository = $this->entityManager->getRepository(Ingredient::class);


        $recipeRecord = $recipeRepository->findOneBy(['name' => 'recipe']);
        $recipeIngredientRecord = $recipeIngredientRepository->findOneBy(['recipe'=>$recipeRecord->getId()]);

        $this->assertEquals('recipe', $recipeRecord->getName());
        $this->assertEquals(['test', 'test1'], $recipeRecord->getInstructions());
        $this->assertEquals(5, $recipeRecord->getServings());
        $this->assertEquals(120, $recipeRecord->getPreparationTime());

        $this->assertEquals(500, $recipeIngredientRecord->getQuantity());
        $this->assertEquals("g", $recipeIngredientRecord->getUnit());
        $this->assertEquals($recipe, $recipeIngredientRecord->getRecipe());
        $this->assertEquals($ingredient, $recipeIngredientRecord->getIngredient());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}