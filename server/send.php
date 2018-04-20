<?php

$localhost = 'tcp://127.0.0.1:4444';
$user = 'vladimir';
$message = 'test message from server...';

$instance = stream_socket_client($localhost);

fwrite($instance, json_encode(['user' => $user, 'message' => $message]) . "\n");