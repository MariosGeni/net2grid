<?php

use App\connectors\Connectors;
use App\Publisher;
use App\Consumer;

require dirname(__DIR__) . '/vendor/autoload.php';

$rabbitMQQueue = 'cand_x07w_results';
$rabbitMQExchange = 'cand_x07w';

$connectors = new Connectors();
$publisher = new Publisher();
$consumer = new Consumer();

$rabbitMQConnection = $connectors->connectToRabbitMQ();
$databaseConnection = $connectors->connectToDatabase();

$publisher->publish_message($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange);
$consumer->consumingData($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange);

$connectors->closeConnections($rabbitMQConnection, $databaseConnection);

