<?php

include_once __DIR__.'/../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

$wsWorker = new Worker("websocket://0.0.0.0:8000");
$wsWorker->count = 4;

$users = [];