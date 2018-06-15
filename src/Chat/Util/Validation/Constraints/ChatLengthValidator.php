<?php

declare(strict_types=1);

namespace Chat\Util\Validation\Constraints;

use Chat\Exception\Action\ValidationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ChatLengthValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     * @throws ValidationException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ChatLength) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ChatLength');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $stringValue = (string) $value;

        if (!$invalidCharset = !@mb_check_encoding($stringValue, $constraint->charset)) {
            $length = mb_strlen($stringValue, $constraint->charset);
        } else {
            throw new ValidationException('Unknown charset error');
        }

        if ($invalidCharset) {
            $this->context->buildViolation($constraint->charsetMessage)
                ->setParameter('{{ value }}', $this->formatValue($stringValue))
                ->setParameter('{{ charset }}', $constraint->charset)
                ->setInvalidValue($value)
                ->addViolation();

            return;
        }

        if (null !== $constraint->max && $length > $constraint->max) {
            $this->context->buildViolation($constraint->min == $constraint->max ? $constraint->exactMessage : $constraint->maxMessage)
                ->setParameter('{{ value }}', $this->formatValue($stringValue))
                ->setParameter('{{ limit }}', $constraint->max)
                ->setInvalidValue($value)
                ->setPlural((int) $constraint->max)
                ->addViolation();

            return;
        }

        if (null !== $constraint->min && $length < $constraint->min) {
            $this->context->buildViolation($constraint->min == $constraint->max ? $constraint->exactMessage : $constraint->minMessage)
                ->setParameter('{{ value }}', $this->formatValue($stringValue))
                ->setParameter('{{ limit }}', $constraint->min)
                ->setInvalidValue($value)
                ->setPlural((int) $constraint->min)
                ->addViolation();
        }
    }
}
