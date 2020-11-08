<?php
declare(strict_types=1);

namespace App\Model\Queue;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class QueueFactory
{
    /**
     * @var QueueConfig
     */
    protected QueueConfig $queueConfig;

    /**
     * @param QueueConfig $queueConfig
     */
    public function __construct(QueueConfig $queueConfig)
    {
        $this->queueConfig = $queueConfig;
    }

    /**
     * @return AMQPStreamConnection
     */
    public function createAMQPStreamConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            $this->queueConfig->getHost(),
            $this->queueConfig->getPort(),
            $this->queueConfig->getUser(),
            $this->queueConfig->getPassword(),
        );
    }
}
