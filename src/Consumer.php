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
    public function consumingData($rabbitMQConnection,$rabbitMQQueue, $rabbitMQExchange){
        $channel = $rabbitMQConnection->channel();

        $channel->queue_declare($rabbitMQQueue, false, true, false, false);
        $channel->exchange_declare($rabbitMQExchange, 'direct', false, true, false);
        $channel->queue_bind($rabbitMQQueue, $rabbitMQExchange);

        $channel->basic_consume($rabbitMQQueue, '', false, false, false, false, 'processMessage');

        /**
         * @param AMQPMessage $message
         * @return void
         *
         * Processing the message, so it can be sent to the database
         */
        function processMessage(AMQPMessage $message)
        {

            $sender = new Message();

            $messageBody = json_decode($message->body);

            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            $routingKey = $message->delivery_info['routing_key'];

            $sender->insertIntoDatabase($routingKey);
        }

        try {
            while (count($channel->callbacks)) {

                $channel->wait(null, true, 3);
            }
        } catch (Exception $e) {
            echo "An error occurred while processing your request";
        }
        $channel->close();
    }


}