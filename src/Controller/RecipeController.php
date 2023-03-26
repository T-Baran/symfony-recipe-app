<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Service\ErrorManager;
use App\Service\FormManagers\RecipeManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/recipes')]
class RecipeController extends ApiController
{
    public function __construct(private RecipeRepository $recipeRepository, private RecipeManager $recipeManager, private ErrorManager $errorManager)
    {
        parent::__construct($this->errorManager);
    }

    #[Route('', name: 'recipe_get', methods: 'GET')]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $recipes = $this->recipeRepository->findAll();
        $data = $serializer->serialize($recipes, 'json',
            ['groups' => ['recipe']]);
        return $this->response($data, [], true);
    }

    #[Route('', name: 'recipe_post', methods: 'POST')]
    public function post(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->recipeManager);
    }

    #[Route('/{id}', name: 'recipe_show', methods: 'GET')]
    public function show(Recipe $recipe, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($recipe, 'json',
            ['groups' => ['recipe', 'recipe_detail']]);
        return $this->response($data, [], true);
    }

    #[Route('/{id}', name: 'recipe_modify', methods: ['PUT', 'PATCH'])]
    public function update(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->recipeManager);
    }

    #[Route('/{id}', name: 'recipe_delete', methods: 'DELETE')]
    public function delete(Recipe $recipe):JsonResponse
    {
        $this->recipeRepository->remove($recipe, true);
        return $this->respondNoContent();
    }
}
