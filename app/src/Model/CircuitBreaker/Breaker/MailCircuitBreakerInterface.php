<?php

declare(strict_types=1);

namespace App\Model\CircuitBreaker\Breaker;

use App\Entity\Mail;
use App\Model\Mail\Adapter\MailClientAdapterInterface;

interface MailCircuitBreakerInterface
{
    /**
     * @param Mail $mail
     *
     * @return bool|null
     */
    public function handle(Mail $mail): ?bool;

    /**
     * @param MailClientAdapterInterface $mailClientAdapter
     *
     * @return MailCircuitBreakerInterface
     */
    public function setMailClientAdapter(
        MailClientAdapterInterface $mailClientAdapter
    ): MailCircuitBreakerInterface;
}
