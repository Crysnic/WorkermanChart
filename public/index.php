<?php

include_once __DIR__.'/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $path =  __DIR__.'/../src/'. str_replace('\\', '/', $class) . '.php';
    /** @noinspection PhpIncludeInspection */
    include_once $path;
});

$x = new \Chat\Kernel\ChatService(__DIR__ . '/../config', '{"Command":"Test"}');
$x->run();
