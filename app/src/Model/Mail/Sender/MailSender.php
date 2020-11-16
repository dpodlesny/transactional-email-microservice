<?php

declare(strict_types=1);

namespace App\Model\Mail\Sender;

use App\Entity\Mail;
use App\Model\CircuitBreaker\Breaker\MailCircuitBreakerInterface;
use App\Model\CircuitBreaker\Exception\MailServicesNotAvailableException;
use App\Model\Mail\Adapter\MailClientAdapterInterface;
use Psr\Log\LoggerInterface;

class MailSender implements MailSenderInterface
{
    /**
     * @var MailClientAdapterInterface
     */
    protected MailClientAdapterInterface $mainMailClient;

    /**
     * @var array<MailClientAdapterInterface>
     */
    protected array $fallbackMailClients;

    /**
     * @var MailCircuitBreakerInterface
     */
    protected MailCircuitBreakerInterface $mailCircuitBreaker;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param MailClientAdapterInterface $mainMailClient
     * @param array<MailClientAdapterInterface> $fallbackMailClients
     * @param MailCircuitBreakerInterface $mailCircuitBreaker
     * @param LoggerInterface $logger
     */
    public function __construct(
        MailClientAdapterInterface $mainMailClient,
        array $fallbackMailClients,
        MailCircuitBreakerInterface $mailCircuitBreaker,
        LoggerInterface $logger
    ) {
        $this->mainMailClient = $mainMailClient;
        $this->fallbackMailClients = $fallbackMailClients;
        $this->mailCircuitBreaker = $mailCircuitBreaker;
        $this->logger = $logger;
    }

    /**
     * @param Mail $mail
     *
     * @return bool
     */
    public function send(Mail $mail): bool
    {
        $isSent = $this->mailCircuitBreaker
            ->setMailClientAdapter($this->mainMailClient)
            ->handle($mail);

        if ($isSent === true) {
            return true;
        }

        foreach ($this->fallbackMailClients as $mailService) {
            $isSent = $this->mailCircuitBreaker
                ->setMailClientAdapter($mailService)
                ->handle($mail);

            if ($isSent) {
                $fallBackClass = get_class($mailService);
                $this->logger->warning("Mail#{$mail->getId()} was sent via fallback mail service: {$fallBackClass}");

                return true;
            }
        }

        if ($isSent === null) {
            $this->logger->critical('Mail services is not responding.');

            throw new MailServicesNotAvailableException();
        }

        return false;
    }
}
