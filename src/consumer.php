<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$host = 'candidatemq.n2g-dev.net';
$port = 5672;
$user = 'cand_x07w';
$vhost = 'cand_x07w';
$pass = 'jsmDH9ZfThk6SWpE';
$exchange = 'cand_x07w';
$queue = 'cand_x07w_results';

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);

function process_message(AMQPMessage $message)
{

    $messageBody = json_decode($message->body);

    echo $messageBody;

    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

    if ($message->body === 'quit'){
        $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
    }
}

$consumerTag = 'local.imac.consumer';

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

function shutdown($channel, $connection){
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

while (count($channel->callbacks)){
    $channel->wait();
}