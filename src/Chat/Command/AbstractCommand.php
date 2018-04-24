<?php

declare(strict_types=1);

namespace Chat\Command;

use Chat\Kernel\Protocol\AnswerBundle;
use Chat\Kernel\Protocol\RequestBundle;
use Chat\Util\Logging\LoggerReferenceTrait;

/**
 * Class AbstractCommand
 * @package Chat\Command
 */
abstract class AbstractCommand
{
    use LoggerReferenceTrait;

    /**
     * @param RequestBundle $requestBundle
     * @return AnswerBundle
     */
    abstract public function handle(RequestBundle $requestBundle): AnswerBundle;
}
