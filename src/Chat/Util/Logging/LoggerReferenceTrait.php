<?php

declare(strict_types=1);

namespace Chat\Util\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class LoggerReferenceTrait
 * @package Regulpay\Util\Logging
 */
trait LoggerReferenceTrait
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Assigns logger
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Returns logger
     *
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        if ($this->logger === null) {
            return DefaultLogger::getInstance();
        }

        return $this->logger;
    }

    /**
     * @param \Exception    $exception
     * @param array         $additionalContext
     * @param string        $level
     * @return void
     * @throws \Exception
     */
    public function throwAndLog(
        \Exception $exception,
        array $additionalContext = [],
        string $level = LogLevel::ERROR
    ) {
        if ($this->getLogger() !== null) {
            $additionalContext['exception'] = $exception;
            if (!isset($additionalContext['object'])) {
                $additionalContext['object'] = $this;
            }
            $this->getLogger()->log($level, $exception->getMessage(), $additionalContext);
        }
        throw $exception;
    }
}
