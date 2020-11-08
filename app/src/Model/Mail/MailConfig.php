<?php

declare(strict_types=1);

namespace App\Model\Mail;

class MailConfig
{
    protected const PAGE_SIZE = 10;

    public const CONTENT_TYPE_HTML = 'text/html';
    public const CONTENT_TYPE_TEXT = 'text/plain';

    protected const ALLOWED_CONTENT_TYPES = [
        self::CONTENT_TYPE_HTML,
        self::CONTENT_TYPE_TEXT,
    ];

    /**
     * @uses \App\Entity\Content::$type
     *
     * @return array<string>
     */
    public static function getAllowedContentTypes(): array
    {
        return static::ALLOWED_CONTENT_TYPES;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return static::PAGE_SIZE;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return 'Dennis Underwood';
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return 'underwood.dv@gmail.com';
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return 'queue.mail';
    }

    /**
     * @return string
     */
    public function getQueueExchangeName(): string
    {
        return 'router';
    }

    /**
     * @return string
     */
    public function getQueueConsumerTag(): string
    {
        return 'consumer';
    }
}
