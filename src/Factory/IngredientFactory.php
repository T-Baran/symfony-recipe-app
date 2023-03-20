<?php

namespace App\Factory;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

final class IngredientFactory extends ModelFactory
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(30),
            'calories' => self::faker()->numberBetween(0,1000),
            'carbohydrates' => self::faker()->numberBetween(0,30),
            'fiber' => self::faker()->numberBetween(0,30),
            'protein' => self::faker()->numberBetween(0,30)
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Ingredient $ingredient): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ingredient::class;
    }
}
