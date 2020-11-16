<?php

declare(strict_types=1);

namespace App\Model\CircuitBreaker;

class CircuitBreakerConfig
{
    /**
     * @return int
     */
    public function getDefaultThreshold(): int
    {
        return 5;
    }

    /**
     * @return int
     */
    public function getDefaultTimeout(): int
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getDefaultTimeoutStep(): int
    {
        return 1;
    }
}
