<?php

declare(strict_types=1);

namespace Chat\Util\Validation\Constraints; 

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ChatNotBlank
 * @package Chat\Util\Validation\Constraints
 */
class ChatNotBlank extends NotBlank
{
    public $message = '<{{ field }}> parameter should not be blank.';
}
