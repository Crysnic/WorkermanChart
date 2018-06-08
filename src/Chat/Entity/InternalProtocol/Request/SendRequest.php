<?php

declare(strict_types=1);

namespace Chat\Entity\InternalProtocol\Request;

/**
 * Class SendRequest
 * @package Chat\Entity\InternalProtocol\Request
 */
class SendRequest extends Request
{
    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $message;

    /**
     * SendRequest constructor.
     * @param string $command
     * @param string $to
     * @param string $message
     */
    public function __construct(string $command, string $to, string $message)
    {
        parent::__construct($command);
        $this->to = $to;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
