<?php

declare(strict_types=1);

namespace Chat\Entity;

use Workerman\Connection\ConnectionInterface;

/**
 * Class WsMessage
 * @package Chat\Entity
 */
class WsMessage
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var string
     */
    private $message;

    /**
     * WsMessage constructor.
     * @param ConnectionInterface $connection
     * @param string $message
     */
    public function __construct(ConnectionInterface $connection, string $message)
    {
        $this->connection = $connection;
        $this->message = $message;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function notifySender(string $message): void
    {
        $this->connection->send($message);
    }
}
