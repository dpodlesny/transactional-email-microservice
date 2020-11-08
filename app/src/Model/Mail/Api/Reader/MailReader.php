<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Reader;

use App\Entity\Mail;
use App\Model\Mail\MailConfig;
use App\Repository\MailRepository;

class MailReader implements MailReaderInterface
{
    protected const KEY_TOTAL = 'total';
    protected const KEY_ITEMS = 'items';

    /**
     * @var MailRepository
     */
    protected MailRepository $mailRepository;

    /**
     * @var MailConfig
     */
    protected MailConfig $mailConfig;

    /**
     * @param MailRepository $mailRepository
     * @param MailConfig $mailConfig
     */
    public function __construct(
        MailRepository $mailRepository,
        MailConfig $mailConfig
    ) {
        $this->mailRepository = $mailRepository;
        $this->mailConfig = $mailConfig;
    }

    /**
     * @param int $page
     *
     * @return array<int|string, array<Mail>|int>
     */
    public function findPaginated(int $page = 1): array
    {
        $limit = $this->mailConfig->getPageSize();
        $offset = $limit * ($page - 1);

        return [
            static::KEY_TOTAL => $this->mailRepository->totalCount(),
            static::KEY_ITEMS => $this->mailRepository->findBy([], [], $limit, $offset),
        ];
    }
}
