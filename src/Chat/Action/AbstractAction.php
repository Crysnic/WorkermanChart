<?php

declare(strict_types=1);

namespace Chat\Action;

use Chat\Kernel\Protocol\RequestBundle;
use Chat\Util\Logging\LoggerReferenceTrait;

/**
 * Class AbstractCommand
 * @package Chat\Command
 */
abstract class AbstractAction
{
    use LoggerReferenceTrait;

    /**
     * @var array
     */
    private $users;

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function setUsers(array &$users)
    {
        $this->users = $users;
    }

    /**
     * @param RequestBundle $requestBundle
     * @return void
     */
    abstract public function handle(RequestBundle $requestBundle): void;
}
