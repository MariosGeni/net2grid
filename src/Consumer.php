<?php

namespace App;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer
{

    /**
     * @param $rabbitMQConnection
     * @param $rabbitMQExchange
     * @param $rabbitMQQueue
     * @return void
     *
     * Consuming a data from RabbitMQ
     */
    public function consumingData($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange, $databaseConnection)
    {
        $channel = $rabbitMQConnection->channel();

        $channel->queue_declare($rabbitMQQueue, false, true, false, false);
        $channel->exchange_declare($rabbitMQExchange, 'direct', false, true, false);
        $channel->queue_bind($rabbitMQQueue, $rabbitMQExchange);

        /**
         * @param AMQPMessage $message
         * @return void
         *
         * This is an internal method that take the message and passing to the sender class
         */
        $callback = function (AMQPMessage $message) use ($databaseConnection) {

            $sender = new Message();

            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            $routingKey = $message->delivery_info['routing_key'];

            $sender->insertIntoDatabase($databaseConnection, $routingKey);
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
            echo "There are no other messages to be consumed or the was been an error during the consumption";
        }
        $channel->close();
    }
}