<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Entity\InternalProtocol\ResponseCode;
use Chat\Kernel\Protocol\RequestBundle;

/**
 * Class Repeat
 * @package Chat\Command
 */
class Repeat extends AbstractAction
{
    /**
     * @param RequestBundle $requestBundle
     * @return void
     */
    public function handle(RequestBundle $requestBundle): void
    {
        $message = json_encode([
            'Result' => ResponseCode::SUCCESS_ACTION,
            'Message' => $requestBundle->getParams()['Message'] ?? ''
        ]);
        $requestBundle->getWsMessage()->notifySender($message);
    }
}
