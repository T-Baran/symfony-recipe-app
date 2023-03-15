<?php

namespace App\Service;

use App\DTO\IngredientDTO;
use App\DTO\RecipeDTO;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\SecurityBundle\Security;

class RecipeManager
{

    public function __construct(private RecipeRepository $recipeRepository, private IngredientRepository $ingredientRepository, private Security $security, private RecipeIngredientRepository $recipeIngredientRepository)
    {
    }

    public function manageRecipe(RecipeDTO $recipeDTO): void
    {
        $recipe = new Recipe();
        $recipeDTO->transferTo($recipe);
        $recipe->setUser($this->security->getUser());
        foreach ($recipeDTO->getIngredients() as $recipeIngredientDTO) {
            $recipeIngredient = new recipeIngredient();
            $recipeIngredientDTO->transferTo($recipeIngredient);
            $ingredient = $this->handleIngredient($recipeIngredientDTO->getIngredient());
            $recipeIngredient->setIngredient($ingredient);
            $recipeIngredient->setRecipe($recipe);
            $this->recipeIngredientRepository->save($recipeIngredient);
        }
        $this->recipeRepository->saveWithFlush($recipe);
    }

    public function handleIngredient(IngredientDTO $ingredientDTO): Ingredient
    {
        if(($id = $ingredientDTO->getId()) === null){
            return $this->createIngredient($ingredientDTO);
        }
        $record = $this->ingredientRepository->find($id);
        $inDatabase = $record->getName() === $ingredientDTO->getName();
        if (!$inDatabase || $ingredientDTO->getId() === null) {
            return $this->createIngredient($ingredientDTO);
        }
        return $record;
    }

    private function createIngredient(IngredientDTO $ingredientDTO):Ingredient
    {
        $ingredient = new Ingredient();
        $ingredientDTO->transferTo($ingredient);
        $ingredient->setUser($this->security->getUser());
        $this->ingredientRepository->save($ingredient);
        return $ingredient;
    }
}