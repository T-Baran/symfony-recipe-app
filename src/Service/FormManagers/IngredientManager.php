<?php

namespace App\Service\FormManagers;


use App\DTO\IngredientDTO;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;

class IngredientManager extends AbstractFormManager
{
    public function __construct(private IngredientRepository $ingredientRepository, private UserManager $userManager)
    {
    }

    public const FORM_TYPE = IngredientType::class;

    public function createDTO(): IngredientDTO
    {
        return new IngredientDTO();
    }

    public function saveRecord($ingredientDTO, $id = null): Ingredient
    {
        if (is_null($id)) {
            $ingredient = $this->createIngredient($ingredientDTO);
        } else {
            $ingredient = $this->ingredientRepository->find($id);
            $ingredientDTO->transferTo($ingredient);
        }
        return $ingredient;
    }

    public function flushRecord($ingredient):void
    {
        $this->ingredientRepository->onlyFlush();
        $this->setId($ingredient->getId());
    }

    public function getLocation(): string
    {
        return '/api/ingredients/' . $this->getId();
    }

    private function createIngredient(IngredientDTO $ingredientDTO): Ingredient
    {
        $ingredient = new Ingredient();
        $ingredientDTO->transferTo($ingredient);
        $this->userManager->setCurrentUser($ingredient);
        $this->ingredientRepository->save($ingredient);
        return $ingredient;
    }
}