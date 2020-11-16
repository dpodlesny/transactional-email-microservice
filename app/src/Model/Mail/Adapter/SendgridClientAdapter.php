<?php

declare(strict_types=1);

namespace App\Model\Mail\Adapter;

use App\Entity\Mail;
use App\Model\Mail\MailConfig;
use Exception;
use Psr\Log\LoggerInterface;
use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;

class SendgridClientAdapter implements MailClientAdapterInterface
{
    /**
     * @var SendGrid
     */
    protected SendGrid $sendGridClient;

    /**
     * @var MailConfig
     */
    protected MailConfig $mailConfig;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param string $sendGridApiKey
     * @param MailConfig $mailConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        string $sendGridApiKey,
        MailConfig $mailConfig,
        LoggerInterface $logger
    ) {
        $this->sendGridClient = new SendGrid($sendGridApiKey);
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
        $email = new SendGridMail();
        $email->setFrom($this->mailConfig->getFromEmail(), $this->mailConfig->getFromName());
        $email->setSubject($mail->getSubject());
        $email->addTo($mail->getRecipient()->getEmail(), $mail->getRecipient()->getName());

        foreach ($mail->getContents() as $content) {
            $email->addContent($content->getType(), $content->getContent());
        }

        foreach ($mail->getAdditionalRecipients() as $additionalRecipient) {
            $email->addCc($additionalRecipient->getEmail(), $additionalRecipient->getName());
        }

        try {
            $response = $this->sendGridClient->send($email);

            if ($response->statusCode() >= 200 && $response->statusCode() <= 299) {
                return true;
            }

            $this->logger->error("Sendgrid Mail#{$mail->getId()} was failed with: " . $response->body());

            return false;
        } catch (Exception $e) {
            $this->logger->error("Sendgrid Mail#{$mail->getId()} was failed with: " . $e->getMessage());

            return false;
        }
    }
}
