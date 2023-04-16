<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\ErrorManager;
use App\Service\FormManagers\RecipeManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;


#[Route('/api/recipes')]
class RecipeController extends ApiController
{
    public function __construct(private RecipeRepository $recipeRepository, private RecipeManager $recipeManager, private ErrorManager $errorManager)
    {
        parent::__construct($this->errorManager);
    }

    #[Route('', name: 'recipe_get', methods: 'GET')]
    #[OA\Response(
        response: 200,
        description: 'Returns the list of recipes',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Recipe::class, groups: ['recipe']))
        )
    )]
    #[OA\Tag(name: 'Recipe')]
    #[Security(name: 'Bearer')]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $recipes = $this->recipeRepository->findAll();
        $data = $serializer->serialize($recipes, 'json',
            ['groups' => ['recipe']]);
        return $this->response($data, [], true);
    }

    #[Route('', name: 'recipe_post', methods: 'POST')]
    #[OA\Response(
        response: 201,
        description: 'successful response',
        headers: [new OA\Header(
            header: 'Location',
            description: 'Use this url to query for your recipe',
            schema: new OA\Schema(type: 'string')
        )]
    )]
    #[OA\Response(
        response: 400,
        description: 'bad response'
    )]
    #[OA\RequestBody(
        description: 'Recipe data structure',
        required: true,
        content: new Model(type: RecipeType::class)
    )]
    #[OA\Tag(name: 'Recipe')]
    #[Security(name: 'Bearer')]
    public function post(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->recipeManager);
    }

    #[Route('/{id}', name: 'recipe_show', methods: 'GET')]
    #[OA\Response(
        response: 200,
        description: 'Returns the recipe with specified Id',
        content: new Model(type: Recipe::class, groups: ['recipe','recipe_detail'])
    )]
    #[OA\Tag(name: 'Recipe')]
    #[Security(name: 'Bearer')]
    public function show(Recipe $recipe, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($recipe, 'json',
            ['groups' => ['recipe', 'recipe_detail']]);
        return $this->response($data, [], true);
    }

    #[Route('/{id}', name: 'recipe_modify', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 204,
        description: 'successful response',
        headers: [new OA\Header(
            header: 'Location',
            description: 'Use this url to query for your recipe',
            schema: new OA\Schema(type: 'string')
        )]
    )]
    #[OA\Response(
        response: 400,
        description: 'bad response'
    )]
    #[OA\RequestBody(
        description: 'Recipe data structure',
        required: true,
        content: new Model(type: RecipeType::class)
    )]
    #[OA\Tag(name: 'Recipe')]
    #[Security(name: 'Bearer')]
    public function update(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->recipeManager);
    }

    #[Route('/{id}', name: 'recipe_delete', methods: 'DELETE')]
    #[OA\Response(
        response: 204,
        description: 'successful response'
    )]
    #[OA\Response(
        response: 405,
        description: 'bad response'
    )]
    #[OA\Tag(name: 'Recipe')]
    #[Security(name: 'Bearer')]
    public function delete(Recipe $recipe):JsonResponse
    {
        $this->recipeRepository->remove($recipe, true);
        return $this->respondNoContent();
    }
}
