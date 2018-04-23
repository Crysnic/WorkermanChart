<?php

declare(strict_types=1);

namespace Chat\Util\Logging;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Formatter\LogstashFormatter;

class DefaultLogger extends Logger
{
    /**
     * @var Logger
     */
    private static $instance = null;

    /**
     * @return Logger
     */
    public static function getInstance() : Logger
    {
        if (self::$instance === null) {
            self::$instance = new Logger('default');
            $handler = new ErrorLogHandler();
            $formatter = new LogstashFormatter('logstash');
            
            self::$instance->pushHandler($handler->setFormatter($formatter));
        }
        
        return self::$instance;
    }
}
