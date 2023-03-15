<?php

namespace App\Controller;

use App\DTO\IngredientDTO;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use App\Service\ErrorManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/ingredients')]
class IngredientController extends ApiController
{
    public function __construct(private IngredientRepository $ingredientRepository, private ErrorManager $errorManager)
    {
    }

    // Querystring params
    // search -> define searched word
    // results -> define how many ingredients should be returned
    #[Route('', name: 'ingredient_get', methods: 'GET')]
    public function index(Request $request, SerializerInterface $serializer): JsonResponse
    {
        if ($query = $request->query->all()) {
            $ingredients = $this->ingredientRepository->findByNameAndResults($query['search'], $query['results']??10);
        } else {
            $ingredients = $this->ingredientRepository->findAll();
        }
            $data = $serializer->serialize($ingredients, 'json');
        return $this->response($data, [], true);
    }

    #[Route('', name: 'ingredient_post', methods: 'POST')]
    public function post(Request $request): JsonResponse
    {
        $ingredient = new Ingredient();
        return $this->handleForm($request, $ingredient);
    }

    #[Route('/{id}', name: 'ingredient_show', methods: 'GET')]
    public function show(Ingredient $ingredient, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($ingredient, 'json');
        return $this->response($data, [], true);
    }

    #[Route('/{id}', name: 'ingredient_modify', methods: ['PUT', 'PATCH'])]
    public function update(Ingredient $ingredient, Request $request): JsonResponse
    {
        return $this->handleForm($request, $ingredient);
    }

    #[Route('/{id}', name: 'ingredient_delete', methods: 'DELETE')]
    public function delete(Ingredient $ingredient): JsonResponse
    {
        $this->ingredientRepository->remove($ingredient, true);
        return $this->respondNoContent();
    }

    public function handleForm(Request $request, Ingredient $ingredient): JsonResponse
    {
        $data = $this->returnTransformedData($request);
        $clearMissing = $request->getMethod() !== 'PATCH';
        $ingredientDTO = new IngredientDTO($ingredient);
        $form = $this->createForm(IngredientType::class, $ingredientDTO);
        $form->submit($data, $clearMissing);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientDTO->transferTo($ingredient);
            $ingredient->setUser($this->getUser());
            $this->ingredientRepository->saveWithFlush($ingredient);
            $isPost = $request->getMethod() === 'POST' ? '201' : '204';
            $this->setStatusCode($isPost);
            return $this->response(null, ['Location' => '/api/ingredients/' . $ingredient->getId()]);
        }
        $this->setStatusCode(400);
        return $this->respondWithErrors($this->errorManager->getErrorsFromForm($form), []);
    }
}
