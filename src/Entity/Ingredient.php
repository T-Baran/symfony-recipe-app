<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe','ingredient'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['recipe','ingredient'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe_detail','ingredient'])]
    private ?int $calories = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe_detail','ingredient'])]
    private ?int $carbohydrates = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe_detail','ingredient'])]
    private ?int $fiber = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe_detail','ingredient'])]
    private ?int $protein = null;

    #[ORM\Column(nullable: true)]
    private ?int $fat = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['ingredient_detail'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: RecipeIngredient::class)]
    private Collection $recipeIngredients;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(?int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getCarbohydrates(): ?int
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?int $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getFiber(): ?int
    {
        return $this->fiber;
    }

    public function setFiber(?int $fiber): self
    {
        $this->fiber = $fiber;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(?int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getIngredient() === $this) {
                $recipeIngredient->setIngredient(null);
            }
        }

        return $this;
    }

    public function getFat(): ?int
    {
        return $this->fat;
    }

    public function setFat(?int $fat): self
    {
        $this->fat = $fat;

        return $this;
    }
}
