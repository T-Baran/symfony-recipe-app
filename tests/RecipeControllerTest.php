<?php

namespace App\Tests;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeControllerTest extends WebTestCase
{
    public function testControllerIndex(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/recipes');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(15, $responseData);
    }

    public function testControllerGet(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/recipes/1');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('preparationTime', $responseData);
        $this->assertArrayHasKey('servings', $responseData);
        $this->assertArrayHasKey('user', $responseData);
        $this->assertArrayHasKey('instructions', $responseData);
    }

    public function testControllerPost(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->fullIngredientData());
        $client->request('POST', '/api/recipes', content: $content);
        $response = $client->getResponse();
        $recipeRecord = $entityManager->getRepository(Recipe::class)->findOneBy(['name' => 'full']);
        $this->basicValidtests(201, $response, $recipeRecord);
        $this->basicvalueTests($this->fullIngredientData(), $recipeRecord);

        $content = json_encode($this->partialValidIngredientData());
        $client->request('POST', '/api/recipes', content: $content);
        $response = $client->getResponse();
        $recipeRecord = $entityManager->getRepository(Recipe::class)->findOneBy(['name' => 'partialValid']);
        $this->basicValidtests(201, $response, $recipeRecord);
        $this->basicvalueTests($this->partialValidIngredientData(), $recipeRecord);

        $content = json_encode($this->partialNotValidIngredientData());
        $client->request('POST', '/api/recipes', content: $content);
        $response = $client->getResponse();
        $this->basicNotValidTests($response);
    }

    public function testControllerPutFullValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->fullIngredientData());
        $client->request('PUT', '/api/recipes/3', content: $content);
        $response = $client->getResponse();
        $recipeRecord = $entityManager->getRepository(Recipe::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $recipeRecord);
        $this->basicvalueTests($this->fullIngredientData(), $recipeRecord, 3);
    }

    public function testControllerPutPartialValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->partialValidIngredientData());
        $client->request('PUT', '/api/recipes/3', content: $content);
        $response = $client->getResponse();
        $recipeRecord = $entityManager->getRepository(Recipe::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $recipeRecord);
        $this->basicvalueTests($this->partialValidIngredientData(), $recipeRecord, 3);
    }

    public function testControllerPutNotValid(): void
    {
        $client = $this->createAuthenticatedClient();

        $content = json_encode($this->partialNotValidIngredientData());
        $client->request('PUT', '/api/ingredients/3', content: $content);
        $response = $client->getResponse();
        $this->basicNotValidTests($response);
    }

    public function testControllerPatchFullValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->fullIngredientData());
        $client->request('PATCH', '/api/recipes/3', content: $content);
        $response = $client->getResponse();
        $recipeRecord = $entityManager->getRepository(Recipe::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $recipeRecord);
        $this->basicvalueTests($this->fullIngredientData(), $recipeRecord, 3);
    }

    public function testControllerPatchPartialValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->partialValidIngredientData());
        $client->request('PATCH', '/api/recipes/3', content: $content);
        $response = $client->getResponse();
        $recipeRecord = $entityManager->getRepository(Recipe::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $recipeRecord);
        $this->basicvalueTests($this->partialValidIngredientData(), $recipeRecord, 3);
    }

    public function testControllerPatchNotValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->partialNotValidIngredientData());
        $client->request('PATCH', '/api/recipes/3', content: $content);
        $recipeRecord = $entityManager->getRepository(Recipe::class)->findOneBy(['id' => 3]);
        $response = $client->getResponse();
        $this->basicValidTests(204, $response, $recipeRecord);
        $this->basicvalueTests($this->partialNotValidIngredientData(), $recipeRecord, 3);
    }

    public function testControllerDeleteSuccess(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $client->request('DELETE', '/api/ingredients/50');
        $response = $client->getResponse();
        $this->assertSame(204, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
        $ingredients = $entityManager->getRepository(Ingredient::class)->findAll();
        $this->assertCount(49, $ingredients);
    }
//
//    public function testControllerDeleteFailure(): void
//    {
//        $client = $this->createAuthenticatedClient();
//        $container = $client->getContainer();
//        $entityManager = $container->get('doctrine')->getManager();
//
//        $client->request('DELETE', '/api/ingredients/1');
//        $response = $client->getResponse();
//        $this->assertSame(405, $response->getStatusCode());
////        dd($response->getContent());
//        $this->assertStringContainsString('Cannot delete if someone is using this ingredient', $response->getContent());
//        $ingredients = $entityManager->getRepository(Ingredient::class)->findAll();
//        $this->assertCount(50, $ingredients);
//    }

    private function fullIngredientData(): array
    {
        return [
            "name" => "full",
            "preparationTime" => 1234,
            "servings" => 5,
            "instructions" => ["test", "test3"],
            "ingredients" => [
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete",
                        "calories" => 500
                    ]
                ],
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete",
                        "calories" => 600
                    ]
                ],
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete"
                    ]
                ]
            ]
        ];
    }

    private
    function partialValidIngredientData(): array
    {
        return [
            "name" => "partialValid",
            "ingredients" => [
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete",
                        "calories" => 500
                    ]
                ],
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete",
                        "calories" => 600
                    ]
                ],
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete"
                    ]
                ]
            ]
        ];
    }

    private
    function partialNotValidIngredientData(): array
    {
        return [
            "preparationTime" => 1234,
            "servings" => 5,
            "instructions" => ["test", "test3"],
            "ingredients" => [
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete",
                        "calories" => 500
                    ]
                ],
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete",
                        "calories" => 600
                    ]
                ],
                [
                    "unit" => "g",
                    "quantity" => 500,
                    "ingredient" => [
                        "name" => "delete"
                    ]
                ]
            ]
        ];
    }

    private function basicValidtests(int $expectedStatusCode, $response, $recipeRecord): void
    {
        $this->assertSame($expectedStatusCode, $response->getStatusCode());
        if ($expectedStatusCode === 204) {
            $this->assertEmpty($response->getContent());
        } else {
            $this->assertJson($response->getContent());
        }
        $this->assertTrue($response->headers->contains('Location', '/api/recipes/' . $recipeRecord->getId()));
    }

    private function basicNotValidTests($response): void
    {
        $this->assertSame(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('type', $data);
        $this->assertContains('validation_error', $data);
    }

    private function basicvalueTests(array $inputArray, $recipeRecord, int|null $id = null): void
    {
        if ($id) {
            $this->assertEquals($id, $recipeRecord->getId());
        }
        if (array_key_exists('name', $inputArray)) {
            $this->assertEquals($inputArray['name'], $recipeRecord->getName());
        }
        if (array_key_exists('servings', $inputArray)) {
            $this->assertEquals($inputArray['servings'], $recipeRecord->getServings());
        }
        if (array_key_exists('preparationTime', $inputArray)) {
            $this->assertEquals($inputArray['preparationTime'], $recipeRecord->getPreparationTime());
        }
        if (array_key_exists('instructions', $inputArray)) {
            $this->assertEquals($inputArray['instructions'], $recipeRecord->getInstructions());
        }
        if (array_key_exists('recipeIngredients', $inputArray)) {
            $this->assertEquals($inputArray['recipeIngredients'], $recipeRecord->getRecipeIngredients());
        }
    }

    private function createAuthenticatedClient($email = 'superadmin@test.test', $password = 'test1234')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
}
