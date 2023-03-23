<?php

namespace App\Service;


use App\DTO\IngredientDTO;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;

class IngredientManager implements FormHandlerInterface
{
    private int $id;

    public function __construct(private IngredientRepository $ingredientRepository, private UserManager $userManager)
    {
    }

    public const FormType = IngredientType::class;

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

    public function flushRecord($ingredient)
    {
        $this->ingredientRepository->onlyFlush();
        $this->setId($ingredient->getId());
    }

    public function getLocation(): string
    {
        return '/api/ingredients/' . $this->getId();
    }

    public function handleIngredientWithId(IngredientDTO $ingredientDTO): Ingredient
    {
        if ((!$id = $ingredientDTO->getId()) || !$ingredient = $this->ingredientRepository->find($id)) {
            return $this->createIngredient($ingredientDTO);
        }
        $ingredientDTO->transferTo($ingredient);
        return $ingredient;
    }

    private function createIngredient(IngredientDTO $ingredientDTO): Ingredient
    {
        $ingredient = new Ingredient();
        $ingredientDTO->transferTo($ingredient);
        $this->userManager->setCurrentUser($ingredient);
        $this->ingredientRepository->save($ingredient);
        return $ingredient;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }


}