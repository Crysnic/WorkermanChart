<?php

declare(strict_types=1);

namespace Chat\Exception\Action;

use Chat\Entity\InternalProtocol\ResponseCode;

/**
 * Class UserNotFoundException
 * @package Chat\Exception\Action
 */
class UserNotFoundException extends ActionException
{
    /**
     * UserNotFoundException constructor.
     * @param $userName
     */
    public function __construct($userName)
    {
        parent::__construct('User <'.$userName.'> not found', ResponseCode::USER_NOT_FOUND);
    }
}
