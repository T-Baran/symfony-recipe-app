<?php

namespace App\DTO;

use App\Entity\RecipeIngredient;
use App\Service\IngredientManager;

class RecipeIngredientDTO
{
    private ?int $id = null;

    private ?string $unit = null;

    private ?int $quantity = null;

    private ?IngredientDTO $ingredient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIngredient(): ?IngredientDTO
    {
        return $this->ingredient;
    }

    public function setIngredient(?IngredientDTO $ingredient): void
    {
        $this->ingredient = $ingredient;
    }

    public function transferTo(RecipeIngredient $recipeIngredient): void
    {
        if (!is_null($unit = $this->getUnit())) {
            $recipeIngredient->setUnit($unit);
        }
        if (!is_null($quantity = $this->getQuantity())) {
            $recipeIngredient->setQuantity($quantity);
        }
    }
}
