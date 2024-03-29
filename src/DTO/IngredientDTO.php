<?php

namespace App\DTO;

use App\Entity\Ingredient;

class IngredientDTO
{

    private ?string $name = null;

    private ?int $id = null;

    private ?int $calories = null;

    private ?int $carbohydrates = null;

    private ?int $fiber = null;

    private ?int $protein = null;

    private ?int $fat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(?int $calories): void
    {
        $this->calories = $calories;
    }

    public function getCarbohydrates(): ?int
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?int $carbohydrates): void
    {
        $this->carbohydrates = $carbohydrates;
    }

    public function getFiber(): ?int
    {
        return $this->fiber;
    }

    public function setFiber(?int $fiber): void
    {
        $this->fiber = $fiber;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(?int $protein): void
    {
        $this->protein = $protein;
    }

    public function getFat(): ?int
    {
        return $this->fat;
    }

    public function setFat(?int $fat): void
    {
        $this->fat = $fat;
    }


    public function transferTo(Ingredient $ingredient): Ingredient
    {
        $ingredient->setName($this->getName());
        $ingredient->setCalories($this->getCalories());
        $ingredient->setCarbohydrates($this->getCarbohydrates());
        $ingredient->setFiber($this->getFiber());
        $ingredient->setProtein($this->getProtein());
        $ingredient->setFat($this->getFat());

        return $ingredient;
    }

    public function transferToOmitNull(Ingredient $ingredient): Ingredient
    {
        if (!is_null($this->getName())) {
            $ingredient->setName($this->getName());
        }
        if (!is_null($this->getCalories())) {
            $ingredient->setCalories($this->getCalories());
        }
        if (!is_null($this->getCarbohydrates())) {
            $ingredient->setCarbohydrates($this->getCarbohydrates());
        }
        if (!is_null($this->getFiber())) {
            $ingredient->setFiber($this->getFiber());
        }
        if (!is_null($this->getProtein())) {
            $ingredient->setProtein($this->getProtein());
        }
        if (!is_null($this->getFat())) {
            $ingredient->setFat($this->getFat());
        }
        return $ingredient;
    }

    public function transferFrom(Ingredient $ingredient): void
    {
        $this->setName($ingredient->getName());
        $this->setCalories($ingredient->getCalories());
        $this->setCarbohydrates($ingredient->getCarbohydrates());
        $this->setFiber($ingredient->getFiber());
        $this->setProtein($ingredient->getProtein());
        $this->setId($ingredient->getId());
        $this->setFat($ingredient->getFat());
    }
}