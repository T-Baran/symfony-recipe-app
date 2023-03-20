<?php

namespace App\Factory;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

final class RecipeFactory extends ModelFactory
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(20),
            'preparationTime' => self::faker()->numberBetween(0,300),
            'servings' => self::faker()->numberBetween(0,10),
            'instructions' => [self::faker()->word(), self::faker()->word()]
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Recipe $recipe): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Recipe::class;
    }
}
