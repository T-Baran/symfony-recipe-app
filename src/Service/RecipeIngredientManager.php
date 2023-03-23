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

    public function saveRecord(RecipeIngredientDTO $recipeIngredientDTO): RecipeIngredient
    {
        if (is_null($id = $recipeIngredientDTO->getId())) {
            $recipeIngredient = $this->createRecipeIngredient($recipeIngredientDTO);
        } else {
            $recipeIngredient = $this->recipeIngredientRepository->find($id);
            if(is_null($recipeIngredient)){
                throw new NotFoundHttpException('The RecipeIngredient with ID ' . $id . ' was not found');
            }
            $recipeIngredientDTO->transferTo($recipeIngredient);
        }
        $ingredientId = $recipeIngredientDTO->getIngredient()->getId();
        $ingredient = $this->ingredientManager->saveRecord($recipeIngredientDTO->getIngredient(),$ingredientId);
        $recipeIngredient->setIngredient($ingredient);
        return $recipeIngredient;
    }

    private function createRecipeIngredient(RecipeIngredientDTO $recipeIngredientDTO): RecipeIngredient
    {
        $recipeIngredient = new RecipeIngredient();
        $recipeIngredientDTO->transferTo($recipeIngredient);
        $this->recipeIngredientRepository->save($recipeIngredient);
        return $recipeIngredient;
    }

    private function returnPersistedRecipeIngredient(RecipeIngredientDTO $recipeIngredientDTO): RecipeIngredient
    {
        if (!$id = $recipeIngredientDTO->getId()) {
            $recipeIngredient = new RecipeIngredient();
            $this->recipeIngredientRepository->save($recipeIngredient);
        } else if (!$recipeIngredient = $this->recipeIngredientRepository->find($id)) {
            throw new NotFoundHttpException('The RecipeIngredient with ID ' . $id . ' was not found');
        }
        return $recipeIngredient;
    }
}