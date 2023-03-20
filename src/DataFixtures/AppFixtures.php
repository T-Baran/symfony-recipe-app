<?php

namespace App\DataFixtures;

use App\Factory\IngredientFactory;
use App\Factory\RecipeFactory;
use App\Factory\RecipeIngredientFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'superadmin@test.test', 'username' => 'superadmin', 'plainPassword' => 'test1234', 'roles' => ['ROLE_SUPER_ADMIN']]);
        UserFactory::createOne(['email' => 'admin@test.test', 'username' => 'admin', 'plainPassword' => 'test1234', 'roles' => ['ROLE_ADMIN']]);
        UserFactory::createOne(['email' => 'mod@test.test', 'username' => 'mod', 'plainPassword' => 'test1234', 'roles' => ['ROLE_EDITOR']]);
        UserFactory::createOne(['email' => 'user@test.test', 'username' => 'user', 'plainPassword' => 'test1234', 'roles' => ['ROLE_USER']]);
        UserFactory::createMany(10);
        IngredientFactory::createMany(49,
            function () {
                return ['user' => UserFactory::random()];
            });
        RecipeFactory::createMany(15,
            function () {
                return ['user' => UserFactory::random()];
            });
        RecipeIngredientFactory::createMany(74,
            function () {
                return ['ingredient' => IngredientFactory::random(),
                    'recipe' => RecipeFactory::random()];
            });

        //Ingredient that can be deleted in tests
        IngredientFactory::createOne(['user'=>UserFactory::random()]);
        //make sure that first ingredient and first recipe are locked for delete operation
        RecipeIngredientFactory::createOne(['ingredient' => IngredientFactory::find(1),
                    'recipe' => RecipeFactory::find(1)]);

        $manager->flush();
    }

}
