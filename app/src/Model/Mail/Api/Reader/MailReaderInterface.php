<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Reader;

interface MailReaderInterface
{
    /**
     * @param int $page
     *
     * @return array
     */
    public function findPaginated(int $page = 1): array;
}
