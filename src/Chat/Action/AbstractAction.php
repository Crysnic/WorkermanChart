<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Exception\Action\ValidationException;
use Chat\Kernel\Protocol\RequestBundle;
use Chat\Util\Logging\LoggerReferenceTrait;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractCommand
 * @package Chat\Command
 */
abstract class AbstractAction
{
    use LoggerReferenceTrait;

    /**
     * @var array
     */
    private $users;

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function setUsers(array &$users)
    {
        $this->users = $users;
    }

    /**
     * @param $userName
     * @param $connection
     */
    public function addUser($userName, $connection): void
    {
        $this->users[$userName] = $connection;
    }

    /**
     * @param array $validationFields
     * @param array $params
     * @return void
     *
     * @throws ValidationException
     */
    protected function validation(array $params, array $validationFields): void
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            'missingFieldsMessage' => '<{{ field }}> is missing.',
            'fields' => $validationFields
        ]);

        $violations = $validator->validate($params, $constraint);
        $this->checkViolations($violations);
    }


    /**
     * @param ConstraintViolationListInterface $violations
     * @return void
     * @throws ValidationException
     */
    private function checkViolations(ConstraintViolationListInterface $violations): void
    {
        if (0 !== count($violations)) {
            $this->getLogger()->info($violations[0]->getMessage());
            throw new ValidationException($violations[0]->getMessage());
        }
    }

    /**
     * @param RequestBundle $requestBundle
     * @return void
     */
    abstract public function handle(RequestBundle $requestBundle): void;
}
