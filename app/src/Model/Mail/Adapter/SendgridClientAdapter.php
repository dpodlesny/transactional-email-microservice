<?php
declare(strict_types=1);

namespace App\Model\Mail\Adapter;

use App\Entity\Mail;
use App\Model\Mail\MailConfig;
use Exception;
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
     * @param string $sendGridApiKey
     * @param MailConfig $mailConfig
     */
    public function __construct(string $sendGridApiKey, MailConfig $mailConfig)
    {
        $this->sendGridClient = new SendGrid($sendGridApiKey);
        $this->mailConfig = $mailConfig;
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

            return $response->statusCode() >= 200 && $response->statusCode() <= 299;
        } catch (Exception $e) {
            return false;
        }
    }
}
