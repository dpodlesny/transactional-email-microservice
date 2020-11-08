<?php

declare(strict_types=1);

namespace App\Model\Mail\Sender;

use App\Entity\Mail;
use App\Model\Mail\Adapter\MailClientAdapterInterface;

class MailSender implements MailSenderInterface
{
    /**
     * @var MailClientAdapterInterface
     */
    protected MailClientAdapterInterface $mainMailClient;

    /**
     * @var array<MailClientAdapterInterface>
     */
    protected array $fallbackMailClients;

    /**
     * @param MailClientAdapterInterface $mainMailClient
     * @param array<MailClientAdapterInterface> $fallbackMailClients
     */
    public function __construct(
        MailClientAdapterInterface $mainMailClient,
        array $fallbackMailClients
    ) {
        $this->mainMailClient = $mainMailClient;
        $this->fallbackMailClients = $fallbackMailClients;
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
