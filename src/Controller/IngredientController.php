<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use App\Service\IngredientManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/api')]
class IngredientController extends AbstractController
{
    public function __construct(private IngredientManager $manager)
    {
    }

    #[Route('/ingredients', name: 'ingredient_get', methods: 'GET')]
    public function index(IngredientRepository $ingredientRepository, SerializerInterface $serializer): JsonResponse
    {
        $ingredients = $ingredientRepository->findAll();
        $data = $serializer->serialize($ingredients, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/ingredients', name: 'ingredient_post', methods: 'POST')]
    public function post(Request $request): JsonResponse
    {
        $ingredient = new Ingredient();
        return $this->manager->generateHandleForm($request, $ingredient);
    }

    #[Route('/ingredients/{id}', name: 'ingredient_show', methods: 'GET')]
    public function show(Ingredient $ingredient, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($ingredient, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/ingredients/{id}', name: 'ingredient_modify', methods: ['PUT', 'PATCH'])]
    public function update(Ingredient $ingredient, Request $request): JsonResponse
    {
        return $this->manager->generateHandleForm($request, $ingredient);
    }

    #[Route('/ingredients/{id}', name: 'ingredient_delete', methods: 'DELETE')]
    public function delete(Ingredient $ingredient): JsonResponse
    {
        $this->manager->delete($ingredient, true);
        return new JsonResponse(null, 204);
    }


}
