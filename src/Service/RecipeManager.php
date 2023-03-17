<?php

namespace App\Service;

use App\DTO\RecipeDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\SecurityBundle\Security;

class RecipeManager
{
    private int $id;

    public function __construct(private RecipeRepository $recipeRepository, private Security $security, private RecipeIngredientManager $recipeIngredientManager, private IngredientManager $ingredientManager)
    {
    }

    public function manageRecipe(RecipeDTO $recipeDTO, Recipe $recipe): void
    {
        $recipeDTO->transferTo($recipe);
        $recipe->setUser($this->security->getUser());
        foreach ($recipeDTO->getIngredients() as $recipeIngredientDTO) {
            $recipeIngredient = $this->recipeIngredientManager->getPersistedRecipeIngredient($recipeIngredientDTO);
            $recipeIngredientDTO->transferTo($recipeIngredient);
            $ingredient = $this->ingredientManager->handleIngredient($recipeIngredientDTO->getIngredient());
            $recipeIngredient->setIngredient($ingredient);
            $recipeIngredient->setRecipe($recipe);
        }
        $this->recipeRepository->saveWithFlush($recipe);
        $this->setId($recipe->getId());
    }

    public function getId(): int
    {
        return $this->id;
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }


}