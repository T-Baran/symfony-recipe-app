<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('recipe')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('recipe')]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups('recipe')]
    private ?int $preparationTime = null;

    #[ORM\Column(nullable: true)]
    #[Groups('recipe')]
    private ?int $servings = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Groups(['recipe'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class, orphanRemoval: true)]
    #[Groups(['recipe'])]
    private Collection $recipeIngredients;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    #[Groups(['recipe'])]
    private array $instructions = [];

    #[ORM\OneToOne(mappedBy: 'recipe', cascade: ['persist', 'remove'])]
    private ?TotalRecipeNutrient $totalRecipeNutrient = null;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getInstructions(): array
    {
        return $this->instructions;
    }

    public function setInstructions(?array $instructions): self
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getTotalRecipeNutrient(): ?TotalRecipeNutrient
    {
        return $this->totalRecipeNutrient;
    }

    public function setTotalRecipeNutrient(TotalRecipeNutrient $totalRecipeNutrient): self
    {
        // set the owning side of the relation if necessary
        if ($totalRecipeNutrient->getRecipe() !== $this) {
            $totalRecipeNutrient->setRecipe($this);
        }

        $this->totalRecipeNutrient = $totalRecipeNutrient;

        return $this;
    }
}
