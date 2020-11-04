<?php
declare(strict_types=1);

namespace App\Model\Mail;

class MailConfig
{
    protected const PAGE_SIZE = 10;

    public const CONTENT_TYPE_HTML = 'text/html';
    public const CONTENT_TYPE_TEXT = 'text/plain';
    public const CONTENT_TYPE_MARKDOWN = 'text/markdown';

    protected const ALLOWED_CONTENT_TYPES = [
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
