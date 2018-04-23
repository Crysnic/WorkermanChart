<?php

declare(strict_types=1);

namespace Chat\Exception\Protocol;

use Chat\Entity\InternalProtocol\ResponseCode;

class WrongFormatException extends ProtocolException
{
    public function __construct($message)
    {
        parent::__construct($message, ResponseCode::WRONG_FORMAT);
    }
}
