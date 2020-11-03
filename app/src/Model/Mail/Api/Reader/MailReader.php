<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Reader;

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
     * @param MailRepository $mailRepository
     */
    public function __construct(MailRepository $mailRepository)
    {
        $this->mailRepository = $mailRepository;
    }

    /**
     * @param int $page
     *
     * @return array
     */
    public function findPaginated(int $page = 1): array
    {
        $limit = MailConfig::getPageSize();
        $offset = $limit * ($page - 1);

        return [
            static::KEY_TOTAL => $this->mailRepository->totalCount(),
            static::KEY_ITEMS => $this->mailRepository->findBy([], [], $limit, $offset),
        ];
    }
}
