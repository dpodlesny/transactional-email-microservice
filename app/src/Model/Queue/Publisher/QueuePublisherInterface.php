<?php

declare(strict_types=1);

namespace App\Model\Queue\Publisher;

interface QueuePublisherInterface
{
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
    ): void;
}
