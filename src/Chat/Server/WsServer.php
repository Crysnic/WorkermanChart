<?php

declare(strict_types=1);

namespace Chat\Server;

use Chat\Entity\WsMessage;
use Chat\Kernel\ChatService;
use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;

class WsServer
{
    /**
     * @var Worker
     */
    private $wsWorker;

    /**
     * @var array
     */
    private $users;

    /**
     * WsServer constructor.
     * @param string $wsUrl
     */
    public function __construct(string $wsUrl)
    {
        $this->wsWorker = new Worker($wsUrl);
        $this->users = [];
    }
    
    public function setEventsHandlers(): void
    {
        $this->setOnConnect();
        $this->setOnClose();
        $this->setOnMessage();
    }
    
    private function setOnConnect()
    {
        $users = &$this->users;
        
        $this->wsWorker->onConnect = function(ConnectionInterface $connection)
        {
            $connection->onWebSocketConnect = function()
            {
                echo "Connected guest\n";
            };
        };
    }
    
    private function setOnClose()
    {
        $users = &$this->users;

        $this->wsWorker->onClose = function(ConnectionInterface $connection) use (&$users)
        {
            $user = array_search($connection, $users);
            if ($user) {
                unset($users[$user]);
                echo "Disconnected ".$user."\n";
            }
        };
    }
    
    private function setOnMessage()
    {
        $users = &$this->users;
        
        $this->wsWorker->onMessage = function(ConnectionInterface $connection, string $data) use (&$users)
        {
            $wsMessage = new WsMessage($connection, $data);

            $x = new ChatService(__DIR__ . '/../../../config', $wsMessage, $users);
            $x->runServer();
        };
    }
    
    public function runServer(): void
    {
        Worker::runAll();
    }
}
