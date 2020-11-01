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

        return $this->createListResponseData($this->mailRepository->findBy([], [], $limit, $offset));
    }

    /**
     * @param int $page
     *
     * @return array
     */
    public function findPendingPaginated(int $page = 1): array
    {
        return $this->createListResponseData($this->mailRepository->findPendingPaginated($page));
    }

    /**
     * @param array $items
     *
     * @return array
     */
    private function createListResponseData(array $items): array
    {
        return [
            static::KEY_TOTAL => $this->mailRepository->totalCount(),
            static::KEY_ITEMS => $items,
        ];
    }
}
