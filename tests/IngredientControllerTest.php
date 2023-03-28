<?php

namespace App\Tests;

use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IngredientControllerTest extends WebTestCase
{
    public function testControllerIndex(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/ingredients');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(50, $responseData);
    }

    public function testControllerGet(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/ingredients/3');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('calories', $responseData);
        $this->assertArrayHasKey('carbohydrates', $responseData);
        $this->assertArrayHasKey('fiber', $responseData);
        $this->assertArrayHasKey('protein', $responseData);
    }

    public function testControllerPost(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->fullIngredientData());
        $client->request('POST', '/api/ingredients', content: $content);
        $response = $client->getResponse();
        $ingredientRecord = $entityManager->getRepository(Ingredient::class)->findOneBy(['name' => 'full']);
        $this->basicValidtests(201, $response, $ingredientRecord);
        $this->basicvalueTests($this->fullIngredientData(), $ingredientRecord);

        $content = json_encode($this->partialValidIngredientData());
        $client->request('POST', '/api/ingredients', content: $content);
        $response = $client->getResponse();
        $ingredientRecord = $entityManager->getRepository(Ingredient::class)->findOneBy(['name' => 'partialValid']);
        $this->basicValidtests(201, $response, $ingredientRecord);
        $this->basicvalueTests($this->partialValidIngredientData(), $ingredientRecord);

        $content = json_encode($this->partialNotValidIngredientData());
        $client->request('POST', '/api/ingredients', content: $content);
        $response = $client->getResponse();
        $this->basicNotValidTests($response);

        $content = json_encode($this->macronutrientsOver100());
        $client->request('POST', '/api/ingredients', content: $content);
        $response = $client->getResponse();
        $this->basicNotValidTests($response);
    }

    public function testControllerPutFullValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->fullIngredientData());
        $client->request('PUT', '/api/ingredients/3', content: $content);
        $response = $client->getResponse();
        $ingredientRecord = $entityManager->getRepository(Ingredient::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $ingredientRecord);
        $this->basicvalueTests($this->fullIngredientData(), $ingredientRecord, 3);
    }

    public function testControllerPutPartialValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->partialValidIngredientData());
        $client->request('PUT', '/api/ingredients/3', content: $content);
        $response = $client->getResponse();
        $ingredientRecord = $entityManager->getRepository(Ingredient::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $ingredientRecord);
        $this->basicvalueTests($this->partialValidIngredientData(), $ingredientRecord, 3);
    }

    public function testControllerPutNotValid(): void
    {
        $client = $this->createAuthenticatedClient();

        $content = json_encode($this->partialNotValidIngredientData());
        $client->request('PUT', '/api/ingredients/3', content: $content);
        $response = $client->getResponse();
        $this->basicNotValidTests($response);
    }

    public function testControllerPutMacronutrientsOver100(): void
    {
        $client = $this->createAuthenticatedClient();

        $content = json_encode($this->macronutrientsOver100());
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
        $client->request('PATCH', '/api/ingredients/3', content: $content);
        $response = $client->getResponse();
        $ingredientRecord = $entityManager->getRepository(Ingredient::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $ingredientRecord);
        $this->basicvalueTests($this->fullIngredientData(), $ingredientRecord, 3);
    }

    public function testControllerPatchPartialValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->partialValidIngredientData());
        $client->request('PATCH', '/api/ingredients/3', content: $content);
        $response = $client->getResponse();
        $ingredientRecord = $entityManager->getRepository(Ingredient::class)->findOneBy(['id' => 3]);
        $this->basicValidtests(204, $response, $ingredientRecord);
        $this->basicvalueTests($this->partialValidIngredientData(), $ingredientRecord, 3);
    }

    public function testControllerPatchNotValid(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $content = json_encode($this->partialNotValidIngredientData());
        $client->request('PATCH', '/api/ingredients/3', content: $content);
        $ingredientRecord = $entityManager->getRepository(Ingredient::class)->findOneBy(['id' => 3]);
        $response = $client->getResponse();
        $this->basicValidTests(204, $response, $ingredientRecord);
        $this->basicvalueTests($this->partialNotValidIngredientData(), $ingredientRecord, 3);
    }

    public function testControllerPatchMacronutrientsOver100(): void
    {
        $client = $this->createAuthenticatedClient();

        $content = json_encode($this->macronutrientsOver100());
        $client->request('PATCH', '/api/ingredients/3', content: $content);
        $response = $client->getResponse();
        $this->basicNotValidTests($response);
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

    public function testControllerDeleteFailure(): void
    {
        $client = $this->createAuthenticatedClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $client->request('DELETE', '/api/ingredients/1');
        $response = $client->getResponse();
        $this->assertSame(405, $response->getStatusCode());
//        dd($response->getContent());
        $this->assertStringContainsString('Cannot delete if someone is using this ingredient', $response->getContent());
        $ingredients = $entityManager->getRepository(Ingredient::class)->findAll();
        $this->assertCount(50, $ingredients);
    }



    private function fullIngredientData(): array
    {
        return [
            'name' => 'full',
            'calories' => 111,
            'carbohydrates' => 10,
            'fiber' => 20,
            'protein' => 30,
            'fat' => 25
        ];
    }

    private function partialValidIngredientData(): array
    {
        return [
            'name' => 'partialValid',
            'calories' => 111,
            'carbohydrates' => 22
        ];
    }

    private function partialNotValidIngredientData(): array
    {
        return [
            'calories' => 111,
            'carbohydrates' => 22
        ];
    }

    private function macronutrientsOver100():array
    {
        return [
            'name' => 'over100',
            'carbohydrates' => 40,
            'fiber'=>40,
            'fat'=>40
        ];
    }

    private function basicValidtests(int $expectedStatusCode, $response, $ingredientRecord): void
    {
        $this->assertSame($expectedStatusCode, $response->getStatusCode());
        if ($expectedStatusCode === 204) {
            $this->assertEmpty($response->getContent());
        } else {
            $this->assertJson($response->getContent());
        }
        $this->assertTrue($response->headers->contains('Location', '/api/ingredients/' . $ingredientRecord->getId()));
    }

    private function basicNotValidTests($response): void
    {
        $this->assertSame(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('type', $data);
        $this->assertContains('validation_error', $data);
    }

    private function basicvalueTests(array $inputArray, $ingredientRecord, int|null $id = null): void
    {
        if ($id) {
            $this->assertEquals($id, $ingredientRecord->getId());
        }
        if (array_key_exists('name', $inputArray)) {
            $this->assertEquals($inputArray['name'], $ingredientRecord->getName());
        }
        if (array_key_exists('calories', $inputArray)) {
            $this->assertEquals($inputArray['calories'], $ingredientRecord->getCalories());
        }
        if (array_key_exists('carbohydrates', $inputArray)) {
            $this->assertEquals($inputArray['carbohydrates'], $ingredientRecord->getCarbohydrates());
        }
        if (array_key_exists('protein', $inputArray)) {
            $this->assertEquals($inputArray['protein'], $ingredientRecord->getProtein());
        }
        if (array_key_exists('fiber', $inputArray)) {
            $this->assertEquals($inputArray['fiber'], $ingredientRecord->getFiber());
        }
        if (array_key_exists('fat', $inputArray)) {
            $this->assertEquals($inputArray['fat'], $ingredientRecord->getFat());
        }
    }

    protected function createAuthenticatedClient($email = 'superadmin@test.test', $password = 'test1234')
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
