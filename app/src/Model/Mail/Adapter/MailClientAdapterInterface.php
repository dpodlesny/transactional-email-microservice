<?php

declare(strict_types=1);

namespace App\Model\Mail\Adapter;

use App\Entity\Mail;

interface MailClientAdapterInterface
{
    /**
     * Specification:
     *  - Sends email with mail service.
     *  - Returns true if mail was sent otherwise false.
     *
     * @param Mail $mail
     *
     * @return bool
     */
    public function send(Mail $mail): bool;
}
