<?php

namespace App\Validator\UserValidator;

use Symfony\Component\Validator\Constraint;

class UniqueEmail extends Constraint
{
    public string $message = 'This email is already in use.';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}