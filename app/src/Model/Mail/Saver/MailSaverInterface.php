<?php

declare(strict_types=1);

namespace App\Model\Mail\Saver;

use App\Entity\Mail;

interface MailSaverInterface
{
    /**
     * Specification:
     *  - Validates a Mail entity.
     *  - Throws an exception if Mail entity is invalid.
     *  - Stores a Mail entity into the DB.
     *
     * @param Mail $mail
     *
     * @return Mail
     */
    public function save(
        Mail $mail
    ): Mail;
}
