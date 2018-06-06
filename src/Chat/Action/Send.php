<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Kernel\Protocol\RequestBundle;
use Workerman\Connection\ConnectionInterface;

class Send extends AbstractAction
{
    /**
     * @param RequestBundle $requestBundle
     * @return void
     */
    public function handle(RequestBundle $requestBundle): void
    {
        $to = $requestBundle->getParams()['To'];
        $message = $requestBundle->getParams()['Message'];

        /** @var ConnectionInterface $userConnection */
        $userConnection = $this->getUsers()[$to];
        $userConnection->send($message);
    }
}
