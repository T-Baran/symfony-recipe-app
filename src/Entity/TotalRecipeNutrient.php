<?php

namespace App\Entity;

use App\Repository\TotalRecipeNutrientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TotalRecipeNutrientRepository::class)]
class TotalRecipeNutrient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['recipe_detail'])]
    private ?int $calories = 0;

    #[ORM\Column]
    #[Groups(['recipe_detail'])]
    private ?int $carbohydrates = 0;

    #[ORM\Column]
    #[Groups(['recipe_detail'])]
    private ?int $fiber = 0;

    #[ORM\Column]
    #[Groups(['recipe_detail'])]
    private ?int $protein = 0;

    #[ORM\Column]
    #[Groups(['recipe_detail'])]
    private ?int $fat = 0;

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
}
