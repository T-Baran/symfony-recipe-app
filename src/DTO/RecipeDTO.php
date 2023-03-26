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

    public function transferTo(Recipe $recipe): Recipe
    {
        $recipe->setName($this->getName());
        $recipe->setPreparationTime($this->getPreparationTime());
        $recipe->setServings($this->getServings());
        $recipe->setInstructions($this->getInstructions());
//        if (!is_null($name = $this->getName())) {
//            $recipe->setName($name);
//        }
//        if (!is_null($preparationTime = $this->getPreparationTime())) {
//            $recipe->setPreparationTime($preparationTime);
//        }
//        if (!is_null($servings = $this->getServings())) {
//            $recipe->setServings($servings);
//        }
//        if (!is_null($instructions = $this->getInstructions())) {
//            $recipe->setInstructions($instructions);
//        }
        return $recipe;
    }
}