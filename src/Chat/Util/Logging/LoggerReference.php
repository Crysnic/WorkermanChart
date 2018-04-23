<?php

declare(strict_types=1);

namespace Chat\Util\Logging;

use Psr\Log\LoggerInterface;

/**
 * Interface LoggerReference
 * @package Regulpay\Util
 */
interface LoggerReference
{
    /**
     * Assigns logger
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Returns logger
     *
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface;
}
