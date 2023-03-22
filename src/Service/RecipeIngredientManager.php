<?php

namespace App\Service;

use App\DTO\RecipeIngredientDTO;
use App\Entity\RecipeIngredient;
use App\Repository\RecipeIngredientRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecipeIngredientManager
{
    public function __construct(private RecipeIngredientRepository $recipeIngredientRepository, private IngredientManager $ingredientManager)
    {
    }

    public function handleRecipeIngredient(RecipeIngredientDTO $recipeIngredientDTO):RecipeIngredient
    {
        $recipeIngredient = $this->returnPersistedRecipeIngredient($recipeIngredientDTO);
        $recipeIngredientDTO->transferTo($recipeIngredient);
        $ingredient = $this->ingredientManager->handleIngredientWithId($recipeIngredientDTO->getIngredient());
        $recipeIngredient->setIngredient($ingredient);
        return $recipeIngredient;
    }

    private function returnPersistedRecipeIngredient(RecipeIngredientDTO $recipeIngredientDTO):RecipeIngredient
    {
        if(!$id = $recipeIngredientDTO->getId()){
            $recipeIngredient = new RecipeIngredient();
            $this->recipeIngredientRepository->save($recipeIngredient);
        }else if(!$recipeIngredient = $this->recipeIngredientRepository->find($id)){
            throw new NotFoundHttpException('The RecipeIngredient with ID ' .$id.' was not found');
        }
        return $recipeIngredient;
    }
}