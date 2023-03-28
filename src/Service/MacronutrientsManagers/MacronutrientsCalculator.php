<?php

namespace App\Service\MacronutrientsManagers;

use App\Entity\Ingredient;

class MacronutrientsCalculator
{
    private int $grams;

    private Ingredient $ingredient;

    public function __construct(Ingredient $ingredient, int $grams)
    {
        $this->ingredient = $ingredient;
        $this->grams = $grams;
    }

    public function calculateTotalIngredientCalories(): int
    {
        return $this->ingredient->getCalories() * $this->grams / 100;
    }

    public function calculateTotalIngredientCarbohydrates(): int
    {
        return $this->ingredient->getCarbohydrates() * $this->grams / 100;
    }

    public function calculateTotalIngredientFiber(): int
    {
        return $this->ingredient->getFiber() * $this->grams / 100;
    }

    public function calculateTotalIngredientProtein(): int
    {
        return $this->ingredient->getProtein() * $this->grams / 100;
    }

    public function calculateTotalIngredientFat(): int
    {
        return $this->ingredient->getFat() * $this->grams / 100;
    }
}