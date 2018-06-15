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
    private $toRecipient;

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
        $this->toRecipient = $to;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getToRecipient(): string
    {
        return $this->toRecipient;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
