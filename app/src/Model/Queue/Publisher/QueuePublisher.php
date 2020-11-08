<?php

declare(strict_types=1);

namespace App\Model\Queue\Publisher;

use App\Model\Queue\QueueFactory;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class QueuePublisher implements QueuePublisherInterface
{
    /**
     * @var QueueFactory
     */
    protected QueueFactory $queueFactory;

    /**
     * @param QueueFactory $queueFactory
     */
    public function __construct(QueueFactory $queueFactory)
    {
        $this->queueFactory = $queueFactory;
    }

    /**
     * @param string $queueName
     * @param string $exchangeName
     * @param string $messageBody
     *
     * @return void
     */
    public function publish(
        string $queueName,
        string $exchangeName,
        string $messageBody
    ): void {
        $connection = $this->queueFactory->createAMQPStreamConnection();
        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, true, false, false);
        $channel->exchange_declare($exchangeName, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind($queueName, $exchangeName);

        $message = new AMQPMessage(
            $messageBody,
            [
                'content_type' => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            ]
        );

        $channel->basic_publish($message, $exchangeName);

        $channel->close();
        $connection->close();
    }
}
