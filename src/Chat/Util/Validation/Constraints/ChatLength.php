<?php

declare(strict_types=1);

namespace Chat\Util\Validation\Constraints;

use Symfony\Component\Validator\Constraints\Length;

/**
 * Class ChatLength
 * @package Chat\Util\Validation\Constraints
 */
class ChatLength extends Length
{
    public $maxMessage = '{{ value }} is too long. It should have {{ limit }} character or less.|{{ value }} is too long. It should have {{ limit }} characters or less.';
    public $minMessage = '{{ value }} is too short. It should have {{ limit }} character or more.|{{ value }} is too short. It should have {{ limit }} characters or more.';
    public $exactMessage = '{{ value }} should have exactly {{ limit }} character.|{{ value }} should have exactly {{ limit }} characters.';
    public $charsetMessage = '{{ value }} does not match the expected {{ charset }} charset.';
}
