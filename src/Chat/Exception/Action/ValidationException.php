<?php

declare(strict_types=1);

namespace Chat\Exception\Action;

use Chat\Entity\InternalProtocol\ResponseCode;

/**
 * Class ValidationException
 * @package Chat\Exception\Action
 */
class ValidationException extends ActionException
{
    /**
     * ValidationException constructor.
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct($message, ResponseCode::VALIDATION_ERROR);
    }
}
