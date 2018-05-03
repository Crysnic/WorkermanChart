<?php

include_once __DIR__.'/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $path =  __DIR__.'/../src/'. str_replace('\\', '/', $class) . '.php';
    /** @noinspection PhpIncludeInspection */
    include_once $path;
});

use Chat\Server\WsServer;

$wsServer = new WsServer("websocket://0.0.0.0:2346");
$wsServer->setEventsHandlers();

$wsServer->run();
