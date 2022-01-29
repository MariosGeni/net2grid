<?php

namespace App;

use PhpAmqpLib\Message\AMQPMessage;

class Publisher
{

    /**
     * @param $rabbitMQConnection
     * @param $rabbitMQQueue
     * @param $rabbitMQExchange
     * @return void
     *
     * This method gets a message from a given url and edits it, so it can be published to the RabbitMQ
     */
    function publish_message($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange){
        $getter = new Getter();

        $messageToCut = $getter->getMessage();
        $routingKey = $this->message_seperator($messageToCut);

        $channel = $rabbitMQConnection->channel();

        $channel->queue_declare($rabbitMQQueue, false, true, false, false);

        $channel->exchange_declare($rabbitMQExchange, 'direct', false, true, false);
        $channel->queue_bind($rabbitMQQueue, $rabbitMQExchange, $routingKey);

        $messageBody = json_encode([
            $messageToCut->value,
            $messageToCut->timestamp
        ]);

        $message = new AMQPMessage($messageBody, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($message, $rabbitMQExchange, $routingKey);
        $channel->close();
    }

    /**
     * @param $messageToCut
     * @return mixed|string
     *
     * This method gets a list and converting it to a string
     */
    function message_seperator($messageToCut)
    {
        $routingKey = "";
        foreach ($messageToCut as $key => $value) {
            if ($key != "value" && $key != "timestamp") {
                if ($routingKey == "") {
                    $routingKey = $value;
                } else {
                    $routingKey = $routingKey . "." . $value;
                }
            }
        }
        return $routingKey;
    }
}