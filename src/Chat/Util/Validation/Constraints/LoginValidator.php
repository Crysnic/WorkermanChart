<?php

declare(strict_types=1);

namespace Chat\Util\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class LoginValidator
 * @package Chat\Util\Validation\Constraints
 */
class LoginValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Login) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Login');
        }

        if (null === $value or !is_string($value) or preg_match('/^[a-zA-Z0-9_]{5,25}$/', $value) != 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
