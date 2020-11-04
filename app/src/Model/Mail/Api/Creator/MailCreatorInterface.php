<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Creator;

use App\Entity\Mail;

interface MailCreatorInterface
{
    /**
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
