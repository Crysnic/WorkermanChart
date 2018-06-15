<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Entity\InternalProtocol\Request\SendRequest;
use Chat\Exception\Action\ValidationException;
use Chat\Kernel\Protocol\RequestBundle;
use Chat\Util\Validation\Constraints\ChatLength;
use Chat\Util\Validation\Constraints\ChatNotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Workerman\Connection\ConnectionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Send extends AbstractAction
{
    /**
     * @param RequestBundle $requestBundle
     * @return void
     *
     * @throws ValidationException
     */
    public function handle(RequestBundle $requestBundle): void
    {
        $request = $this->validation($requestBundle->getParams());

        /** @var ConnectionInterface $userConnection */
        $userConnection = $this->getUsers()[$request->getTo()];
        $userConnection->send($request->getMessage());
    }

    /**
     * @param array $params
     * @return SendRequest
     *
     * @throws ValidationException
     */
    private function validation(array $params): SendRequest
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            'fields' => [
                'Command' => new ChatNotBlank(),
                'To' => [new ChatNotBlank(), new ChatLength(['min' => 3, 'max' => 25])],
                'Message' => [new ChatNotBlank(), new ChatLength(['max' => 255])]
            ],
            'missingFieldsMessage' => '<{{ field }}> is missing.'
        ]);

        $violations = $validator->validate($params, $constraint);
        $this->checkViolations($violations);

        return new SendRequest(
            $params['Command'],
            $params['To'],
            $params['Message']
        );
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
}
