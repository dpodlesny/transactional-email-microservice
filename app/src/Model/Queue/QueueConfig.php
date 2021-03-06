<?php

declare(strict_types=1);

namespace App\Model\Queue;

class QueueConfig
{
    protected string $host;
    protected string $port;
    protected string $user;
    protected string $password;

    /**
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $password
     */
    public function __construct(
        string $host,
        string $port,
        string $user,
        string $password
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
