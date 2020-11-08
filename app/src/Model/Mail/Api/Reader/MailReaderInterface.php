<?php

declare(strict_types=1);

namespace App\Model\Mail\Api\Reader;

use App\Entity\Mail;

interface MailReaderInterface
{
    /**
     * Specification:
     *  - Returns paginated list of Mail objects.
     *
     * @param int $page
     *
     * @return array<int|string, array<Mail>|int>
     */
    public function findPaginated(int $page = 1): array;
}
