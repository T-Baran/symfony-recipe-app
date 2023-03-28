<?php

namespace App\Service\FormManagers;

use App\DTO\RecipeDTO;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;

class RecipeManager extends AbstractFormManager
{
    public function __construct(private RecipeRepository $recipeRepository, private RecipeIngredientManager $recipeIngredientManager, private UserManager $userManager)
    {
    }

    public const FORM_TYPE = RecipeType::class;

    public function createDTO($id = null): RecipeDTO
    {
        $DTO = new RecipeDTO();
        if(!is_null($id)){
            $record = $this->recipeRepository->find($id);
            $this->setRecord($record);
            $DTO->transferFrom($record);
        }
        return $DTO;
    }

    public function saveRecord($recipeDTO): Recipe
    {
        if (is_null($this->getRecord())) {
            $recipe = $this->createRecipe($recipeDTO);
        } else {
            $recipe = $this->getRecord();
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
}