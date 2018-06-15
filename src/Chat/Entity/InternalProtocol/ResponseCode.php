<?php

declare(strict_types=1);

namespace Chat\Entity\InternalProtocol;

/**
 * Class ResponseCode
 * @package System\Entity\InternalProtocol
 */
class ResponseCode
{
    const UNKNOWN_ERROR = -200;
    const DATA_NOT_FOUND = -121;
    const DATABASE_ERROR = -120;
    const WRONG_FORMAT = -110;
    const UNZIP_ERROR = -109;
    const DUPLICATE_COMMAND = -101;
    const UNKNOWN_COMMAND = -100;
    const AUTH_ERROR = -95;
    const VALIDATION_ERROR = -92;
    const INVALID_ARGUMENT = -91;
    const MISSING_ARGUMENT = -90;
    const SUCCESS_ACTION = 10;
}
