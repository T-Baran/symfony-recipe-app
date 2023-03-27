<?php

namespace App\Controller;

use App\DTO\IngredientDTO;
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

#[Route('/api/ingredients')]
class IngredientController extends ApiController
{
    public function __construct(private IngredientRepository $ingredientRepository, private RecipeIngredientRepository $recipeIngredientRepository, private IngredientManager $ingredientManager, private ErrorManager $errorManager, private SerializerInterface $serializer)
    {
        parent::__construct($this->errorManager);
    }

    // Querystring params
    // search -> define searched word
    // limit -> define how many ingredients should be returned
    #[Route('', name: 'ingredient_get', methods: 'GET')]
    public function index(Request $request): JsonResponse
    {
        if ($query = $request->query->all()) {
            $ingredients = $this->ingredientRepository->findByNameAndLimit($query['search'], $query['limit']??10);
        } else {
            $ingredients = $this->ingredientRepository->findAll();
        }
            $data = $this->serializer->serialize($ingredients, 'json',
                ['groups' => ['ingredient']]);
        return $this->response($data, [], true);
    }

    #[Route('', name: 'ingredient_post', methods: 'POST')]
    public function post(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->ingredientManager);
    }

    #[Route('/{id}', name: 'ingredient_show', methods: 'GET')]
    public function show(Ingredient $ingredient): JsonResponse
    {
        $data = $this->serializer->serialize($ingredient, 'json',
            ['groups' => ['ingredient','ingredient_detail']]);
        return $this->response($data, [], true);
    }

    #[Route('/{id}', name: 'ingredient_modify', methods: ['PUT', 'PATCH'])]
    public function update(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->ingredientManager);
    }

    #[Route('/{id}', name: 'ingredient_delete', methods: 'DELETE')]
    public function delete(Ingredient $ingredient): JsonResponse
    {
        if(!$this->recipeIngredientRepository->checkIfIngredientCanBeDeleted($ingredient->getId())){
            $this->setStatusCode(405);
            return $this->respondWithErrors('Cannot delete if someone is using this ingredient');
        }
        $this->ingredientRepository->remove($ingredient, true);
        return $this->respondNoContent();
    }
}
