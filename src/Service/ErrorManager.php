<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

class ErrorManager
{
    public function getErrorsFromForm(FormInterface $form): array
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