<?php

use App\connectors\Connectors;
use App\RabbitMQPublisher;
use App\RabbitMQConsumer;

require dirname(__DIR__) . '/vendor/autoload.php';

    $rabbitMQQueue = 'cand_7l73_results';
    $rabbitMQExchange = 'cand_7l73';

    $connectors = new Connectors();
    $publisher = new RabbitMQPublisher();
    $consumer = new RabbitMQConsumer();

    $rabbitMQConnection = $connectors->connectToRabbitMQ();
    $databaseConnection = $connectors->connectToDatabase();

    for ($x = 0 ; $x <= 10 ; $x++) {
        $publisher->publish_message($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange);
    }
    $consumer->consumingData($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange, $databaseConnection);

    $connectors->closeConnections($rabbitMQConnection, $databaseConnection);

