<?php

namespace App\Service;

use App\DTO\IngredientDTO;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IngredientManager
{

    public function __construct(private FormFactoryInterface $formFactory, private EntityManagerInterface $entityManager)
    {
    }

    public function generateHandleForm(Request $request, Ingredient $ingredient)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $clearMissing = $request->getMethod() !== 'PATCH';
        $ingredientDTO = new IngredientDTO();
        $ingredientDTO = $this->transferData($ingredient, $ingredientDTO);
        $form = $this->formFactory->create(IngredientType::class, $ingredientDTO);
        $form->submit($data, $clearMissing);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $this->transferData($ingredientDTO, $ingredient);
            $this->save($ingredient, true);
            $isPost = $request->getMethod() === 'POST' ? '201' : '204';
            return new JsonResponse(null, $isPost, ['Location' => '/api/ingredients/' . $ingredient->getId()]);
        }
        return new JsonResponse($this->getErrorsFromForm($form), 400);
    }

    public function save(Ingredient $ingredient, bool $flush = false): void
    {
        $this->entityManager->persist($ingredient);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function delete(Ingredient $ingredient, bool $flush = false): void
    {
        $this->entityManager->remove($ingredient);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    private function transferData(IngredientDTO|Ingredient $fromIngredient, Ingredient|IngredientDTO $toIngredient)
    {
        $toIngredient->setName($fromIngredient->getName());
        $toIngredient->setCalories($fromIngredient->getCalories());
        $toIngredient->setCarbohydrates($fromIngredient->getCarbohydrates());
        $toIngredient->setFiber($fromIngredient->getFiber());
        $toIngredient->setProtein($fromIngredient->getProtein());
        return $toIngredient;
    }


    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return [
            'type' => 'validation_error',
            'title' => 'There was a validation error',
            'errors' => $errors
        ];
    }
}