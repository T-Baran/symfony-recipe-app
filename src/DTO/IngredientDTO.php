<?php

namespace App\DTO;

use App\Entity\Ingredient;

class IngredientDTO
{

    private ?string $name;

    private ?int $id;

    private ?int $calories;

    private ?int $carbohydrates;

    private ?int $fiber;

    private ?int $protein;

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

    public function transferTo(Ingredient $ingredient):Ingredient
    {
//        $ingredient->setName($this->getName());
//        $ingredient->setCalories($this->getCalories());
//        $ingredient->setCarbohydrates($this->getCarbohydrates());
//        $ingredient->setFiber($this->getFiber());
//        $ingredient->setProtein($this->getProtein());
        if (!is_null($name = $this->getName())) {
            $ingredient->setName($name);
        }
        if (!is_null($calories = $this->getCalories())) {
            $ingredient->setCalories($calories);
        }
        if (!is_null($carbohydrates = $this->getCarbohydrates())) {
            $ingredient->setCarbohydrates($carbohydrates);
        }
        if (!is_null($fiber = $this->getFiber())) {
            $ingredient->setFiber($fiber);
        }
        if (!is_null($protein = $this->getProtein())) {
            $ingredient->setProtein($protein);
        }
        return $ingredient;
    }
}