<?php

namespace App\Validator\UserValidator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends  ConstraintValidator
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        $existingUser = $this->userRepository->findOneBy([
            'email' => $value,
        ]);

        if ($existingUser) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}