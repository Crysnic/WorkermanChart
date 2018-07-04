<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Entity\InternalProtocol\Request\AuthorizationRequest;
use Chat\Entity\InternalProtocol\ResponseCode;
use Chat\Entity\Repository\Profile;
use Chat\Exception\Action\ValidationException;
use Chat\Kernel\Protocol\RequestBundle;
use Chat\Util\Validation\Constraints\ChatLength;
use Chat\Util\Validation\Constraints\ChatNotBlank;
use Chat\Util\Validation\Constraints\Login;

class Authorization extends AbstractAction
{
    /**
     * @param RequestBundle $requestBundle
     * @return void
     *
     * @throws ValidationException
     */
    public function handle(RequestBundle $requestBundle): void
    {
        $request = $this->validate($requestBundle->getParams());
        $profile = $this->getProfile($request);

        $user = array_search($requestBundle->getWsMessage()->getConnection(), $this->getUsers());
        if ($user) {
            $message = json_encode([
                'Result' => ResponseCode::SUCCESS_ACTION,
                'Message' => 'User has authorized'
            ]);

            $userConnection = $this->getUsers()[$profile->getName()];
            $userConnection->send(json_encode($message));
        }

        $this->addUser($profile->getName(), $requestBundle->getWsMessage()->getConnection());
        echo "Success authorization by <{$profile->getName()}>\n";

        $message = json_encode([
            'Result' => ResponseCode::SUCCESS_ACTION,
            'Message' => 'Authorization is success'
        ]);

        $userConnection = $this->getUsers()[$profile->getName()];
        $userConnection->send(json_encode($message));
    }

    /**
     * @param array $params
     * @return AuthorizationRequest
     *
     * @throws ValidationException
     */
    private function validate(array $params): AuthorizationRequest
    {
        $this->validation($params, [
            'Command' => new ChatNotBlank(),
            'Login' => [new Login()],
            'Password' => [new ChatNotBlank(), new ChatLength(['min' => 8, 'max' => 25])]
        ]);

        return new AuthorizationRequest(
            $params['Command'],
            $params['Login'],
            $params['Password']
        );
    }

    /**
     * @param AuthorizationRequest $request
     * @return Profile
     * @todo Add getting data from db
     */
    private function getProfile(AuthorizationRequest $request): Profile
    {
        return new Profile($request->getLogin());
    }
}
