<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Kernel\Protocol\AnswerBundle;
use Chat\Kernel\Protocol\RequestBundle;
use Chat\Util\Logging\LoggerReferenceTrait;

/**
 * Class AbstractCommand
 * @package Chat\Command
 */
abstract class AbstractAction
{
    use LoggerReferenceTrait;

    /**
     * @param RequestBundle $requestBundle
     * @return AnswerBundle
     */
    abstract public function handle(RequestBundle $requestBundle): AnswerBundle;
}