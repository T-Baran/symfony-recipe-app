<?php

namespace App\Service;


use App\DTO\IngredientDTO;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Bundle\SecurityBundle\Security;

class IngredientManager
{
    private ?int $id;

    public function __construct(private IngredientRepository $ingredientRepository, private Security $security)
    {
    }

    public function handleIngredient(IngredientDTO $ingredientDTO, ?Ingredient $ingredient = null)
    {
        if (is_null($ingredient)) {
            $ingredient = $this->createIngredient($ingredientDTO);
        } else {
            $ingredientDTO->transferTo($ingredient);
        }
        $this->ingredientRepository->onlyFlush();
        $this->setId($ingredient->getId());
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
        $ingredient->setUser($this->security->getUser());
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