<?php
declare(strict_types=1);

namespace App\Model\Mail;

class MailConfig
{
    private const PAGE_SIZE = 10;

    private const CONTENT_TYPE_HTML = 'text/html';
    private const CONTENT_TYPE_TEXT = 'text/plain';
    private const CONTENT_TYPE_MARKDOWN = 'text/markdown';

    private const ALLOWED_CONTENT_TYPES = [
        self::CONTENT_TYPE_HTML,
        self::CONTENT_TYPE_TEXT,
        self::CONTENT_TYPE_MARKDOWN,
    ];

    /**
     * @return string[]
     */
    public static function getAllowedContentTypes(): array
    {
        return static::ALLOWED_CONTENT_TYPES;
    }

    /**
     * @return int
     */
    public static function getPageSize(): int
    {
        return static::PAGE_SIZE;
    }
}
