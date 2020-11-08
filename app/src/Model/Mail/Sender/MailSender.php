<?php
declare(strict_types=1);

namespace App\Model\Mail\Sender;

use App\Entity\Mail;
use App\Model\Mail\Adapter\MailClientAdapterInterface;

class MailSender implements MailSenderInterface
{
    protected MailClientAdapterInterface $mainMailClient;

    /**
     * @var MailClientAdapterInterface[]
     */
    protected array $fallbackMailClients;

    /**
     * @param MailClientAdapterInterface $mainAdapter
     * @param MailClientAdapterInterface[] $fallbackMailAdapter
     */
    public function __construct(
        MailClientAdapterInterface $mainAdapter,
        array $fallbackMailAdapter
    ) {
        $this->mainMailClient = $mainAdapter;
        $this->fallbackMailClients = $fallbackMailAdapter;
    }

    /**
     * @param Mail $mail
     *
     * @return bool
     */
    public function send(Mail $mail): bool
    {
        if ($this->mainMailClient->send($mail)) {
            return true;
        }

        foreach ($this->fallbackMailClients as $mailService) {
            if ($mailService->send($mail)) {
                return true;
            }
        }

        return false;
    }
}
