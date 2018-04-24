<?php

declare(strict_types=1);

namespace Chat\Command;

use Chat\Entity\InternalProtocol\ResponseCode;
use Chat\Kernel\Protocol\AnswerBundle;
use Chat\Kernel\Protocol\RequestBundle;

/**
 * Class Repeat
 * @package Chat\Command
 */
class Repeat extends AbstractCommand
{
    /**
     * @param RequestBundle $requestBundle
     * @return AnswerBundle
     */
    public function handle(RequestBundle $requestBundle): AnswerBundle
    {
        return new AnswerBundle([
            'Result' => ResponseCode::SUCCESS_ACTION,
            'Message' => $requestBundle->getParams()['Message']
        ]);
    }
}
