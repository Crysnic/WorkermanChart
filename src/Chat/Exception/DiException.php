<?php

namespace Chat\Exception;

/**
 * Class DiException
 * @package System\Exception
 */
class DiException extends \Exception
{
    /**
     * DiException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct('DependencyInjection: '.$message);
    }
}
