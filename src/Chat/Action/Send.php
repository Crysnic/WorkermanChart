<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Entity\InternalProtocol\Request\SendRequest;
use Chat\Exception\Action\UserNotFoundException;
use Chat\Exception\Action\ValidationException;
use Chat\Kernel\Protocol\RequestBundle;
use Chat\Util\Validation\Constraints\ChatLength;
use Chat\Util\Validation\Constraints\ChatNotBlank;
use Workerman\Connection\ConnectionInterface;

class Send extends AbstractAction
{
    /**
     * @param RequestBundle $requestBundle
     * @return void
     *
     * @throws ValidationException
     * @throws UserNotFoundException
     */
    public function handle(RequestBundle $requestBundle): void
    {
        $request = $this->validate($requestBundle->getParams());

        if (!isset($this->getUsers()[$request->getTo()])) {
            throw new UserNotFoundException($request->getTo());
        }
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
    private function validate(array $params): SendRequest
    {
        $this->validation(
            $params,
            [
                'Command' => new ChatNotBlank(),
                'To' => [new ChatNotBlank(), new ChatLength(['min' => 3, 'max' => 25])],
                'Message' => [new ChatNotBlank(), new ChatLength(['max' => 255])]
            ]
        );

        return new SendRequest(
            $params['Command'],
            $params['To'],
            $params['Message']
        );
    }
}
