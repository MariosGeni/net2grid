<?php

namespace App\connectors;

use mysqli;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connectors
{

    /**
     * @return mysqli|void
     * Making a connection with the database
     */
    public function connectToDatabase()
    {
        $databaseServername = "candidaterds.n2g-dev.net";
        $databaseUsername = "cand_zktu";
        $databasePassword = "lba0fektR02t3SPI";
        $dbname = "cand_zktu";

        $databaseConnection = new mysqli($databaseServername, $databaseUsername, $databasePassword, $dbname);

        if ($databaseConnection->connect_error) {
            die("Connection failed: " . $databaseConnection->connect_error);
        }
        return $databaseConnection;
    }

    /**
     * @return AMQPStreamConnection
     * Making a connection with RabbitMQ
     */
    public function connectToRabbitMQ(): AMQPStreamConnection
    {
        $rabbitMQHost = 'candidatemq.n2g-dev.net';
        $rabbitMQPort = 5672;
        $rabbitMQUser = 'cand_7l73';
        $rabbitMQPass = 'n2wHFnWTLRC0yt2';
        $rabbitMQVHost = '/';

        return new AMQPStreamConnection($rabbitMQHost, $rabbitMQPort, $rabbitMQUser, $rabbitMQPass, $rabbitMQVHost);
    }

    /**
     * @param $rabbitMQConnection
     * @param $databaseConnection
     * @return void
     *
     * Closing the connections of database and RabbitMQ
     */
    function closeConnections($rabbitMQConnection, $databaseConnection): void
    {
        $rabbitMQConnection->close();
        $databaseConnection->close();
    }
}