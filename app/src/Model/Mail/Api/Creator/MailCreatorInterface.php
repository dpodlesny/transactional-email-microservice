<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Creator;

use App\Entity\Mail;

interface MailCreatorInterface
{
    /**
     * Specification:
     *  - Creates Mail entity from a data string (e.x. "json").
     *  - Validates a Mail entity.
     *  - Throws an exception if Mail entity is invalid.
     *  - Stores a Mail entity into the DB.
     *  - Pushes message with Mail entity to queue.
     *
     * @param string $mailData
     * @param string $type
     *
     * @return Mail
     */
    public function createFromType(
        string $mailData,
        string $type
    ): Mail;
}
