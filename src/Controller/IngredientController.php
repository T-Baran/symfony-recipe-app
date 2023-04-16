<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Service\ErrorManager;
use App\Service\FormManagers\IngredientManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

#[Route('/api/ingredients')]
class IngredientController extends ApiController
{
    public function __construct(private IngredientRepository $ingredientRepository, private RecipeIngredientRepository $recipeIngredientRepository, private IngredientManager $ingredientManager, private ErrorManager $errorManager, private SerializerInterface $serializer)
    {
        parent::__construct($this->errorManager);
    }

    #[Route('', name: 'ingredient_get', methods: 'GET')]
    #[OA\Parameter(
        name: 'search',
        description: 'Keyword you want to search for',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Limit the ingredients',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the list of ingredient',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Ingredient::class, groups: ['ingredient']))
        )
    )]
    #[OA\Tag(name: 'Ingredient')]
    #[Security(name: 'Bearer')]
    public function index(Request $request): JsonResponse
    {
        if ($query = $request->query->all()) {
            $ingredients = $this->ingredientRepository->findByNameAndLimit($query['search'], $query['limit'] ?? 10);
        } else {
            $ingredients = $this->ingredientRepository->findAll();
        }
        $data = $this->serializer->serialize($ingredients, 'json',
            ['groups' => ['ingredient']]);
        return $this->response($data, [], true);
    }

    #[Route('', name: 'ingredient_post', methods: 'POST')]
    #[OA\Response(
        response: 201,
        description: 'successful response',
        headers: [new OA\Header(
            header: 'Location',
            description: 'Use this url to query for your ingredient',
            schema: new OA\Schema(type: 'string')
        )]
    )]
    #[OA\Response(
        response: 400,
        description: 'bad response'
    )]
    #[OA\RequestBody(
        description: 'Ingredient data structure',
        required: true,
        content: new Model(type: IngredientType::class)
    )]
    #[OA\Tag(name: 'Ingredient')]
    #[Security(name: 'Bearer')]
    public function post(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->ingredientManager);
    }

    #[Route('/{id}', name: 'ingredient_show', methods: 'GET')]
    #[OA\Response(
        response: 200,
        description: 'Returns the ingredient with specified Id',
        content: new Model(type: Ingredient::class, groups: ['ingredient','ingredient_detail'])
    )]
    #[OA\Tag(name: 'Ingredient')]
    #[Security(name: 'Bearer')]
    public function show(Ingredient $ingredient): JsonResponse
    {
        $data = $this->serializer->serialize($ingredient, 'json',
            ['groups' => ['ingredient', 'ingredient_detail']]);
        return $this->response($data, [], true);
    }

    #[Route('/{id}', name: 'ingredient_modify', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 204,
        description: 'successful response',
        headers: [new OA\Header(
            header: 'Location',
            description: 'Use this url to query for your ingredient',
            schema: new OA\Schema(type: 'string')
        )]
    )]
    #[OA\Response(
        response: 400,
        description: 'bad response'
    )]
    #[OA\RequestBody(
        description: 'Ingredient data structure',
        required: true,
        content: new Model(type: IngredientType::class)
    )]
    #[OA\Tag(name: 'Ingredient')]
    #[Security(name: 'Bearer')]
    public function update(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->ingredientManager);
    }

    #[Route('/{id}', name: 'ingredient_delete', methods: 'DELETE')]
    #[OA\Response(
        response: 204,
        description: 'successful response'
    )]
    #[OA\Response(
        response: 405,
        description: 'bad response'
    )]
    #[OA\Tag(name: 'Ingredient')]
    #[Security(name: 'Bearer')]
    public function delete(Ingredient $ingredient): JsonResponse
    {
        if (!$this->recipeIngredientRepository->checkIfIngredientCanBeDeleted($ingredient->getId())) {
            $this->setStatusCode(405);
            return $this->respondWithErrors('Cannot delete if someone is using this ingredient');
        }
        $this->ingredientRepository->remove($ingredient, true);
        return $this->respondNoContent();
    }
}
