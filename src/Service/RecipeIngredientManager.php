<?php

namespace App\Service;

use App\DTO\RecipeIngredientDTO;
use App\Entity\RecipeIngredient;
use App\Repository\RecipeIngredientRepository;

class RecipeIngredientManager
{
    public function __construct(private RecipeIngredientRepository $recipeIngredientRepository)
    {
    }

    public function getPersistedRecipeIngredient(RecipeIngredientDTO $recipeIngredientDTO):RecipeIngredient
    {
        if(!$id = $recipeIngredientDTO->getId()){
            $recipeIngredient = new RecipeIngredient();
            $this->recipeIngredientRepository->save($recipeIngredient);
        }else{
            $recipeIngredient = $this->recipeIngredientRepository->find($id);
        }
        return $recipeIngredient;
    }
}