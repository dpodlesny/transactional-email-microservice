<?php

declare(strict_types=1);

namespace App\Model\CircuitBreaker\Breaker;

use App\Entity\Mail;
use App\Model\CircuitBreaker\CircuitBreakerConfig;
use App\Model\Mail\Adapter\MailClientAdapterInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class MailCircuitBreaker implements MailCircuitBreakerInterface
{
    /**
     * @var CircuitBreakerConfig
     */
    private CircuitBreakerConfig $circuitBreakerConfig;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var MailClientAdapterInterface|null
     */
    private ?MailClientAdapterInterface $mailClientAdapter = null;

    /**
     * @var int
     */
    private int $maxTimeout;

    /**
     * @param CircuitBreakerConfig $circuitBreakerConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        CircuitBreakerConfig $circuitBreakerConfig,
        LoggerInterface $logger
    ) {
        $this->circuitBreakerConfig = $circuitBreakerConfig;
        $this->logger = $logger;
    }

    /**
     * @param MailClientAdapterInterface $mailClientAdapter
     *
     * @return MailCircuitBreakerInterface
     */
    public function setMailClientAdapter(
        MailClientAdapterInterface $mailClientAdapter
    ): MailCircuitBreakerInterface {
        $this->mailClientAdapter = $mailClientAdapter;

        return $this;
    }

    /**
     * @param Mail $mail
     *
     * @return bool|null
     */
    public function handle(Mail $mail): ?bool
    {
        if ($this->mailClientAdapter === null) {
            throw new RuntimeException('Mail client adapter must be set first.');
        }

        $attempt = 0;
        $response = null;
        $this->maxTimeout = $this->circuitBreakerConfig->getDefaultTimeout();

        while ($response === null || $attempt <= $this->circuitBreakerConfig->getDefaultThreshold()) {
            $attempt++;
            do {
                $startTime = microtime(true);

                /** @var null|bool $response */
                $response = $this->mailClientAdapter->send($mail);
            } while (
                $response === null
                || microtime(true) - $startTime >= $this->getIncrementedMaxTimeout()
            );
        }

        if ($response === true) {
            $message = "Mail id #{$mail->getId()} was sent with attempt:{$attempt}"
                       . " after reaching the timeout:{$this->maxTimeout}";
            $this->logger->info($message);

            return true;
        }

        $message = "Mail id #{$mail->getId()} was not sent with attempt:{$attempt}"
                   . " after reaching the timeout:{$this->maxTimeout}";
        $this->logger->error($message);

        return $response;
    }

    /**
     * @return int
     */
    protected function getIncrementedMaxTimeout(): int
    {
        $step = $this->circuitBreakerConfig->getDefaultTimeoutStep();

        return $this->maxTimeout += $step;
    }
}
