<?php

namespace App\Controller;

use App\DTO\RecipeDTO;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use App\Service\ErrorManager;
use App\Service\RecipeManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/recipes')]
class RecipeController extends ApiController
{
    public function __construct(private RecipeRepository $recipeRepository, private ErrorManager $errorManager, private IngredientRepository $ingredientRepository,private RecipeManager $recipeManager)
    {
    }

    #[Route('', name: 'recipe_get', methods: 'GET')]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $recipes = $this->recipeRepository->findAll();
        $data = $serializer->serialize($recipes, 'json');
        return $this->response($data, [], true);
    }

    #[Route('', name: 'recipe_post', methods: 'POST')]
    public function post(Request $request): JsonResponse
    {
        $recipe = new Recipe();
        return $this->handleForm($request, $recipe);
    }

    public function handleForm(Request $request, Recipe $recipe): JsonResponse
    {
        $data = $this->returnTransformedData($request);
        $clearMissing = $request->getMethod() !== 'PATCH';
        $recipeDTO = new RecipeDTO($recipe);
        $form = $this->createForm(RecipeType::class, $recipeDTO);
        $form->submit($data, $clearMissing);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $this->recipeManager->manageRecipe($formData);
            $isPost = $request->getMethod() === 'POST' ? '201' : '204';
            $this->setStatusCode($isPost);
            return $this->response(null, ['Location' => '/api/recipes/' . $recipe->getId()]);
        }
        $this->setStatusCode(400);
        return $this->respondWithErrors($this->errorManager->getErrorsFromForm($form), []);
    }
}
