<?php

namespace App\Entity;

use App\Repository\TotalRecipeNutrientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TotalRecipeNutrientRepository::class)]
class TotalRecipeNutrient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $calories = null;

    #[ORM\Column]
    private ?int $carbohydrates = null;

    #[ORM\Column]
    private ?int $fiber = null;

    #[ORM\Column]
    private ?int $protein = null;

    #[ORM\Column]
    private ?int $fat = null;

    #[ORM\OneToOne(inversedBy: 'totalRecipeNutrient', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getCarbohydrates(): ?int
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(int $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getFiber(): ?int
    {
        return $this->fiber;
    }

    public function setFiber(int $fiber): self
    {
        $this->fiber = $fiber;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getFat(): ?int
    {
        return $this->fat;
    }

    public function setFat(int $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}
