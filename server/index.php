<?php

include_once __DIR__.'/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $path =  __DIR__.'/../src/'. str_replace('\\', '/', $class) . '.php';
    /** @noinspection PhpIncludeInspection */
    include_once $path;
});

use Workerman\Worker;
use Workerman\Connection\ConnectionInterface;

$wsWorker = new Worker("websocket://0.0.0.0:2346");

$users = [];

$wsWorker->onConnect = function(ConnectionInterface $connection) use (&$users)
{
    $connection->onWebSocketConnect = function($webConnection) use (&$users)
    {
        if (isset($_GET['user']) and $_GET['user'] != '' and preg_match('/^[a-zA-Z0-9_]+$/', $_GET['user'])) {
            $users[$_GET['user']] = $webConnection;
            echo "Connected ".$_GET['user']."\n";
        }
    };
};

$wsWorker->onClose = function(ConnectionInterface $connection) use (&$users)
{
    $user = array_search($connection, $users);
    if ($user) {
        unset($users[$user]);
        echo "Disconnected ".$user."\n";
    }
};

$wsWorker->onMessage = function(ConnectionInterface $connection, string $data) use (&$users)
{
    $x = new \Chat\Kernel\ChatService(__DIR__ . '/../config', $data);
    $x->run();
};

$wsWorker->onWorkerStart = function() use (&$users)
{
    $innerTcpWorker = new Worker("tcp://127.0.0.1:4444");
    $innerTcpWorker->onMessage = function(ConnectionInterface $connection, $jsonData) use (&$users) {
        $data = json_decode($jsonData);

        if (isset($users[$data->user])) {
            $webconnection = $users[$data->user];
            $webconnection->send($data->message);
        }
    };
    $innerTcpWorker->listen();
};


Worker::runAll();
