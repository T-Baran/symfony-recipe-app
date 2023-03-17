<?php

namespace App\Service;


use App\DTO\IngredientDTO;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Bundle\SecurityBundle\Security;

class IngredientManager
{
    public function __construct(private IngredientRepository $ingredientRepository, private Security $security)
    {
    }

    public function handleIngredient(IngredientDTO $ingredientDTO): Ingredient
    {
        if (is_null($id = $ingredientDTO->getId())) {
            return $this->createIngredient($ingredientDTO);
        }
        if($ingredient = $this->ingredientRepository->find($id)){
            $ingredientDTO->transferTo($ingredient);
        }else{
         $ingredient = $this->createIngredient($ingredientDTO);
        }
        return $ingredient;
    }

    private function createIngredient(IngredientDTO $ingredientDTO): Ingredient
    {
        $ingredient = new Ingredient();
        $ingredientDTO->transferTo($ingredient);
        $ingredient->setUser($this->security->getUser());
        $this->ingredientRepository->save($ingredient);
        return $ingredient;
    }
}