<?php

declare(strict_types=1);

namespace Chat\Exception\Protocol;

class ProtocolException extends \Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
