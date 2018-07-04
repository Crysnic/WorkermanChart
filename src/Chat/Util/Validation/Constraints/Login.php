<?php

declare(strict_types=1);

namespace Chat\Util\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

class Login extends Constraint
{
    public $message = '<{{ value }}> parameter must be valid login';
}
