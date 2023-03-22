<?php

namespace App\DTO;

use App\Entity\Ingredient;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientDTO
{

    private ?string $name = null;

    private ?int $id = null;

    private ?int $calories = null;

    private ?int $carbohydrates = null;

    private ?int $fiber = null;

    private ?int $protein = null;



//    public function __construct(Ingredient $ingredient)
//    {
//        $this->setName($ingredient->getName());
//        $this->setCalories($ingredient->getCalories());
//        $this->setCarbohydrates($ingredient->getCarbohydrates());
//        $this->setFiber($ingredient->getFiber());
//        $this->setProtein($ingredient->getProtein());
//    }

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

    public function transferTo(Ingredient $ingredient)
    {
        if(!is_null($name = $this->getName())){
            $ingredient->setName($name);
        }
        if(!is_null($calories = $this->getCalories())){
            $ingredient->setCalories($calories);
        }
        if(!is_null($carbohydrates = $this->getCarbohydrates())){
            $ingredient->setCarbohydrates($carbohydrates);
        }
        if(!is_null($fiber = $this->getFiber())){
            $ingredient->setFiber($fiber);
        }
        if(!is_null($protein = $this->getProtein())){
            $ingredient->setProtein($protein);
        }
    }
}