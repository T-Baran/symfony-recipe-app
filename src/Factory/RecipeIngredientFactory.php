<?php

namespace App\Factory;

use App\Entity\RecipeIngredient;
use App\Repository\RecipeIngredientRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

final class RecipeIngredientFactory extends ModelFactory
{
    private array $unitArray = ['g', 'ml', 'tbsp', 'tsp', 'cup', 'pinch'];

    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'quantity' => self::faker()->numberBetween(0, 500),
            'unit' => self::faker()->randomElement($this->unitArray)
        ];
    }

    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(RecipeIngredient $recipeIngredient): void {})
            ;
    }

    protected static function getClass(): string
    {
        return RecipeIngredient::class;
    }
}
