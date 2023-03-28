<?php

namespace App\Service\MacronutrientsManagers;

use App\Entity\Recipe;

class MacronutrientsManager
{
    private int $calories = 0;

    private int $carbohydrates = 0;

    private int $fiber = 0;

    private int $protein = 0;

    private int $fat = 0;



    public function saveRecord(Recipe $recipe):void
    {
        $recipeIngredients = $recipe->getRecipeIngredients();
        foreach($recipeIngredients as $recipeIngredient){
            $ingredient = $recipeIngredient->getIngredient();
            $grams = UnitConverter::convertToGrams($recipeIngredient->getQuantity(), $recipeIngredient->getUnit());
            $calculator = new MacronutrientsCalculator($ingredient, $grams);
            $this->addMacronutrients($calculator);
        }
    }

    private function addMacronutrients(MacronutrientsCalculator $calculator):void
    {
        $this->addCalories($calculator->calculateTotalIngredientCalories())
            ->addCarbohydrates($calculator->calculateTotalIngredientCarbohydrates())
            ->addFat($calculator->calculateTotalIngredientFat())
            ->addFiber($calculator->calculateTotalIngredientFiber())
            ->addProtein($calculator->calculateTotalIngredientProtein());
    }

    private function addCalories(int $amount):self
    {
        $this->calories+=$amount;
        return $this;
    }
    private function addCarbohydrates(int $amount):self
    {
        $this->carbohydrates+=$amount;
        return $this;
    }
    private function addFiber(int $amount):self
    {
        $this->fiber+=$amount;
        return $this;
    }
    private function addProtein(int $amount):self
    {
        $this->protein+=$amount;
        return $this;
    }
    private function addFat(int $amount):self
    {
        $this->fat+=$amount;
        return $this;
    }

}