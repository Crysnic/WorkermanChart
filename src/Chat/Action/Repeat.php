<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Entity\InternalProtocol\ResponseCode;
use Chat\Kernel\Protocol\AnswerBundle;
use Chat\Kernel\Protocol\RequestBundle;

/**
 * Class Repeat
 * @package Chat\Command
 */
class Repeat extends AbstractAction
{
    /**
     * @param RequestBundle $requestBundle
     * @return AnswerBundle
     */
    public function handle(RequestBundle $requestBundle): AnswerBundle
    {
        return new AnswerBundle([
            AnswerBundle::RESULT_KEY => ResponseCode::SUCCESS_ACTION,
            'Message' => $requestBundle->getParams()['Message'] ?? ''
        ]);
    }
}
