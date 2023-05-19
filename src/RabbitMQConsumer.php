<?php

namespace App;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumer
{

    /**
     * @param $rabbitMQConnection
     * @param $rabbitMQQueue
     * @param $rabbitMQExchange
     * @param $databaseConnection
     * @return void
     *
     * Consuming a data from RabbitMQ
     */
    public function consumingData($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange, $databaseConnection): void
    {
        $channel = $rabbitMQConnection->channel();

        $channel->queue_bind($rabbitMQQueue, $rabbitMQExchange);

        /**
         * @param AMQPMessage $message
         * @return void
         *
         * This is an internal method that take the message and passing to the sender class
         */
        $callback = function (AMQPMessage $message) use ($databaseConnection) {

            $sender = new MessageManipulator();

            $message->getChannel()->basic_ack($message->getDeliveryTag());
            $routingKey = $message->getRoutingKey();

            $sender->insertMessageToDatabase($databaseConnection, $routingKey);
        };

        $channel->basic_consume($rabbitMQQueue, '', false, false, false, false, $callback);

        /**
         * @param AMQPMessage $message
         * @return void
         *
         * Processing the message, so it can be sent to the database
         */


        try {
            while (count($channel->callbacks)) {

                $channel->wait(null, true, 3);
            }
        } catch (Exception $e) {
            echo "There are no other messages to be consumed or the was been an error during the consumption \n".$e;
        }
        $channel->close();
    }
}