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

    public function createDTO($id = null): IngredientDTO
    {
        $ingredientDTO = new ingredientDTO();
        if(!is_null($id)){
            $record = $this->ingredientRepository->find($id);
            $this->setRecord($record);
            $ingredientDTO->transferFrom($record);
        }
        return $ingredientDTO;
    }

    public function saveRecord($ingredientDTO, $updateId = null): Ingredient
    {
        if(!is_null($updateId)){
            $ingredient = $this->ingredientRepository->find($updateId);
            $ingredientDTO->transferTo($ingredient);
            return $ingredient;
        }
        if (is_null($this->getRecord())) {
            $ingredient = $this->createIngredient($ingredientDTO);
        } else {
            $ingredient = $this->getRecord();
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