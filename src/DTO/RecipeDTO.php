<?php

namespace App\DTO;

use App\Entity\Recipe;

class RecipeDTO
{
    private ?string $name = null;

    private ?int $preparationTime = null;

    private ?int $servings = null;

    private ?array $instructions = [];

    private ?array $ingredients = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(?int $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getServings(): ?int
    {
        return $this->servings;
    }

    public function setServings(?int $servings): self
    {
        $this->servings = $servings;

        return $this;
    }

    public function getInstructions(): ?array
    {
        return $this->instructions;
    }

    public function setInstructions(?array $instructions): self
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getIngredients(): ?array
    {
        return $this->ingredients;
    }

    public function setIngredients(?array $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function addIngredient(RecipeIngredientDTO $recipeIngredientDTO):self
    {
        $this->ingredients[] = $recipeIngredientDTO;

        return $this;
    }

    public function transferTo(Recipe $recipe): Recipe
    {
        $recipe->setName($this->getName());
        $recipe->setPreparationTime($this->getPreparationTime());
        $recipe->setServings($this->getServings());
        $recipe->setInstructions($this->getInstructions());
        return $recipe;
    }

    public function transferFrom(Recipe $recipe):void
    {
        $this->setName($recipe->getName());
        $this->setPreparationTime($recipe->getPreparationTime());
        $this->setServings($recipe->getServings());
        $this->setInstructions($recipe->getInstructions());
        foreach($recipe->getRecipeIngredients() as $recipeIngredient){
            $recipeIngredientDTO = new RecipeIngredientDTO();
            $recipeIngredientDTO->transferFrom($recipeIngredient);
            $this->addIngredient($recipeIngredientDTO);
        }
    }
}