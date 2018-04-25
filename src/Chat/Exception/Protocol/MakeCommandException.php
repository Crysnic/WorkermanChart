<?php

declare(strict_types=1);

namespace Chat\Exception\Protocol;

use Chat\Entity\InternalProtocol\ResponseCode;

class MakeCommandException extends ProtocolException
{
    public function __construct()
    {
        parent::__construct('Stop command', ResponseCode::UNKNOWN_ERROR);
    }
}
