<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$host = 'roedeer.rmq.cloudamqp.com';
$port = 5672;
$user = 'ykarradj';
$vhost = 'ykarradj';
$pass = '0FsDc6lUWTrB2qaFQaITdbyBxLcugaHC';
$exchange = 'router';
$queue = 'msgs';

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);

$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);
$messageBody = json_encode([
    'email' => 'john.doe@derp.com',
    'subscribed' => true
]);
$message = new AMQPMessage($messageBody, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($message, $exchange);
$channel->close();
$connection->close();