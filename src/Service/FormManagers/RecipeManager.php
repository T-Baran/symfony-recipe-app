<?php

namespace App\Service\FormManagers;

use App\DTO\RecipeDTO;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;

class RecipeManager implements FormHandlerInterface
{
    private int $id;

    public function __construct(private RecipeRepository $recipeRepository, private RecipeIngredientManager $recipeIngredientManager, private UserManager $userManager)
    {
    }

    public const FORM_TYPE = RecipeType::class;

    public function createDTO(): RecipeDTO
    {
        return new RecipeDTO();
    }

    public function saveRecord($recipeDTO, $id): Recipe
    {
        if (is_null($id)) {
            $recipe = $this->createRecipe($recipeDTO);
        } else {
            $recipe = $this->recipeRepository->find($id);
            $recipeDTO->transferTo($recipe);
        }
        foreach ($recipeDTO->getIngredients() as $recipeIngredientDTO) {
            $recipeIngredient = $this->recipeIngredientManager->saveRecord($recipeIngredientDTO);
            $recipeIngredient->setRecipe($recipe);
        }
        return $recipe;
    }

    public function flushRecord($recipe)
    {
        $this->recipeRepository->saveWithFlush($recipe);
        $this->setId($recipe->getId());
    }

    public function getLocation(): string
    {
        return '/api/recipes/' . $this->getId();
    }

    private function createRecipe(RecipeDTO $recipeDTO): Recipe
    {
        $recipe = new Recipe();
        $recipeDTO->transferTo($recipe);
        $this->userManager->setCurrentUser($recipe);
        $this->recipeRepository->save($recipe);
        return $recipe;
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