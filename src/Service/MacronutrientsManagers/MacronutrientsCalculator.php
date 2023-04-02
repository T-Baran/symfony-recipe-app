<?php

namespace App\Service\MacronutrientsManagers;

use App\Entity\Ingredient;

class MacronutrientsCalculator
{
    private int $grams;

    private array $ingredient;

    public function __construct(array $ingredient, int $grams)
    {
        $this->ingredient = $ingredient;
        $this->grams = $grams;
    }

    public function calculateTotalIngredientCalories(): int
    {
        return $this->ingredient['calories'] * $this->grams / 100;
    }

    public function calculateTotalIngredientCarbohydrates(): int
    {
        return $this->ingredient['carbohydrates'] * $this->grams / 100;
    }

    public function calculateTotalIngredientFiber(): int
    {
        return $this->ingredient['fiber'] * $this->grams / 100;
    }

    public function calculateTotalIngredientProtein(): int
    {
        return $this->ingredient['protein'] * $this->grams / 100;
    }

    public function calculateTotalIngredientFat(): int
    {
        return $this->ingredient['fat'] * $this->grams / 100;
    }
}