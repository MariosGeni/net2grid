<?php

namespace App;

use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher
{

    /**
     * @param $rabbitMQConnection
     * @param $rabbitMQQueue
     * @param $rabbitMQExchange
     * @return void
     *
     * This method gets a message from a given url and edits it, so it can be published to the RabbitMQ
     */
    function publish_message($rabbitMQConnection, $rabbitMQQueue, $rabbitMQExchange): void
    {
        $messageGetter = new MessageGetter();

        $message = $messageGetter->getMessage();
        $routingKey = $this->message_separator($message);

        $channel = $rabbitMQConnection->channel();

        $channel->queue_bind($rabbitMQQueue, $rabbitMQExchange, $routingKey);

        $messageBody = json_encode([
            $message->value,
            $message->timestamp
        ]);

        $AMQPMessage = new AMQPMessage($messageBody, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($AMQPMessage, $rabbitMQExchange, $routingKey);
        $channel->close();
    }

    /**
     * @param $messageToCut
     * @return mixed|string
     *
     * This method gets a list and converting it to a string
     */
    function message_separator($messageToCut): mixed
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