<?php

declare(strict_types=1);

namespace App\Model\Mail\Sender;

use App\Entity\Mail;

interface MailSenderInterface
{
    /**
     * Specification:
     *  - Sends email with mail services.
     *  - If main mail services is not available sends with fallback service.
     *  - Returns true if mail was sent otherwise false.
     *
     * @param Mail $mail
     *
     * @return bool
     */
    public function send(Mail $mail): bool;
}
