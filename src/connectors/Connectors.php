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
        $databaseUsername = "cand_x07w";
        $databasePassword = "jsmDH9ZfThk6SWpE";
        $dbname = "cand_x07w";

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
        $rabbitMQHost = 'roedeer.rmq.cloudamqp.com';
        $rabbitMQPort = 5672;
        $rabbitMQUser = 'ykarradj';
        $rabbitMQPass = '0FsDc6lUWTrB2qaFQaITdbyBxLcugaHC';
        $rabbitMQVHost = 'ykarradj';

        return new AMQPStreamConnection($rabbitMQHost, $rabbitMQPort, $rabbitMQUser, $rabbitMQPass, $rabbitMQVHost);
    }

    /**
     * @param $rabbitMQConnection
     * @param $databaseConnection
     * @return void
     *
     * Closing the connections of database and RabbitMQ
     */
    function closeConnections($rabbitMQConnection, $databaseConnection)
    {
        $rabbitMQConnection->close();
        $databaseConnection->close();
    }
}