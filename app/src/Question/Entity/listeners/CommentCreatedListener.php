<?php

declare(strict_types=1);

namespace App\Question\Entity\listeners;

use App\Question\Entity\Question\events\CommentCreated;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class CommentCreatedListener
{
    public function handle(CommentCreated $event): void
    {
        $exchange = 'ex1';
        $queue = 'q1';

        $connection = new AMQPStreamConnection('rmq', 5672, 'guest', 'guest', '/');
        $channel = $connection->channel();
//        $channel = $connection->channel();
//
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange);

        $message = new AMQPMessage($event->text, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($message, $exchange);

        $channel->close();
        $connection->close();
    }
}
