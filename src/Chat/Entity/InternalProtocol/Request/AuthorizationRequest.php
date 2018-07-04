<?php

declare(strict_types=1);

namespace Chat\Entity\InternalProtocol\Request;

/**
 * Class AuthorizationRequest
 * @package Chat\Entity\InternalProtocol\Request
 */
class AuthorizationRequest extends Request
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * AuthorizationRequest constructor.
     * @param string $command
     * @param string $login
     * @param string $password
     */
    public function __construct(string $command, string $login, string $password)
    {
        parent::__construct($command);
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
