<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class IngredientDTO
{
    #[Assert\NotBlank]
    private ?string $name = null;

    private ?int $calories = null;

    private ?int $carbohydrates = null;

    private ?int $fiber = null;

    private ?int $protein = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getCalories(): ?int
    {
        return $this->calories;
    }

    /**
     * @param int|null $calories
     */
    public function setCalories(?int $calories): void
    {
        $this->calories = $calories;
    }

    /**
     * @return int|null
     */
    public function getCarbohydrates(): ?int
    {
        return $this->carbohydrates;
    }

    /**
     * @param int|null $carbohydrates
     */
    public function setCarbohydrates(?int $carbohydrates): void
    {
        $this->carbohydrates = $carbohydrates;
    }

    /**
     * @return int|null
     */
    public function getFiber(): ?int
    {
        return $this->fiber;
    }

    /**
     * @param int|null $fiber
     */
    public function setFiber(?int $fiber): void
    {
        $this->fiber = $fiber;
    }

    /**
     * @return int|null
     */
    public function getProtein(): ?int
    {
        return $this->protein;
    }

    /**
     * @param int|null $protein
     */
    public function setProtein(?int $protein): void
    {
        $this->protein = $protein;
    }

}