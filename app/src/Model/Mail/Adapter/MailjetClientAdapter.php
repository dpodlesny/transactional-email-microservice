<?php

declare(strict_types=1);

namespace App\Model\Mail\Adapter;

use App\Entity\Mail;
use App\Model\Mail\MailConfig;
use Mailjet\Client;
use Mailjet\Resources;
use Psr\Log\LoggerInterface;

class MailjetClientAdapter implements MailClientAdapterInterface
{
    /**
     * @var Client
     */
    protected Client $mailjetClient;

    /**
     * @var MailConfig
     */
    protected MailConfig $mailConfig;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param string $mailjetApiKeyPublic
     * @param string $mailjetApiKeyPrivate
     * @param MailConfig $mailConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        string $mailjetApiKeyPublic,
        string $mailjetApiKeyPrivate,
        MailConfig $mailConfig,
        LoggerInterface $logger
    ) {
        $this->mailjetClient = new Client(
            $mailjetApiKeyPublic,
            $mailjetApiKeyPrivate,
            true,
            ['version' => 'v3.1']
        );
        $this->mailConfig = $mailConfig;
        $this->logger = $logger;
    }

    /**
     * @param Mail $mail
     *
     * @return bool
     */
    public function send(Mail $mail): bool
    {
        $htmlPart = '';
        $textPart = '';

        foreach ($mail->getContents() as $content) {
            if ($content->getType() === MailConfig::CONTENT_TYPE_HTML) {
                $htmlPart = $content->getContent();
                continue;
            }

            if ($content->getType() === MailConfig::CONTENT_TYPE_TEXT) {
                $textPart = $content->getContent();
                continue;
            }
        }

        $cc = [];

        foreach ($mail->getAdditionalRecipients() as $additionalRecipient) {
            $cc[] = [
                'Email' => $additionalRecipient->getEmail(),
                'Name' => $additionalRecipient->getName(),
            ];
        }

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->mailConfig->getFromEmail(),
                        'Name' => $this->mailConfig->getFromName(),
                    ],
                    'To' => [
                        [
                            'Email' => $mail->getRecipient()->getEmail(),
                            'Name' => $mail->getRecipient()->getName(),
                        ],
                    ],
                    'CC' => $cc,
                    'Subject' => $mail->getSubject(),
                    'TextPart' => $textPart,
                    'HTMLPart' => $htmlPart,
                ],
            ],
        ];

        $response = $this->mailjetClient->post(Resources::$Email, ['body' => $body]);

        if ($response->success() === false) {
            $encodedBody = json_encode($response->getBody());
            $this->logger->error("Sending {$mail->getId()} via Mailjet was failed with: {$encodedBody}");
        }

        return $response->success();
    }
}
