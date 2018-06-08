<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Entity\InternalProtocol\Request\SendRequest;
use Chat\Kernel\Protocol\RequestBundle;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;
use Workerman\Connection\ConnectionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Send extends AbstractAction
{
    /**
     * @param RequestBundle $requestBundle
     * @return void
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
     */
    private function validation(array $params): SendRequest
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            'fields' => [
                'Command' => new Assert\NotBlank(['message' => '<Command> parameter should not be blank.']),
                'To' => new Assert\NotBlank(['message' => '<To> parameter should not be blank.']),
                'Message' => new Assert\NotBlank(['message' => '<Message> parameter should not be blank.'])
            ],
            'missingFieldsMessage' => '<{{ field }}> is missing.'
        ]);

        $violations = $validator->validate($params, $constraint);
        if (0 !== count($violations)) {
            $this->getLogger()->info($violations[0]->getMessage());
            throw new ValidatorException($violations[0]->getMessage());
        }

        return new SendRequest(
            $params['Command'],
            $params['To'],
            $params['Message']
        );
    }
}
