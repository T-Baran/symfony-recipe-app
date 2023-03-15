<?php

namespace App\DTO;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

class RecipeIngredientDTO
{
    private ?string $unit = null;

    private ?int $quantity = null;

    private ?IngredientDTO $ingredient = null;

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

    public function transferTo(RecipeIngredient $recipeIngredient):void
    {
        $recipeIngredient->setUnit($this->getUnit());
        $recipeIngredient->setQuantity($this->getQuantity());
    }
}
