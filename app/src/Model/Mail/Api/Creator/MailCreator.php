<?php

declare(strict_types=1);

namespace App\Model\Mail\Api\Creator;

use App\Entity\Mail;
use App\Model\Mail\MailConfig;
use App\Model\Mail\Saver\MailSaverInterface;
use App\Model\Queue\Publisher\QueuePublisherInterface;
use JsonException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class MailCreator implements MailCreatorInterface
{
    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var MailSaverInterface
     */
    protected MailSaverInterface $mailSaver;

    /**
     * @var MailConfig
     */
    protected MailConfig $mailConfig;

    /**
     * @var QueuePublisherInterface
     */
    protected QueuePublisherInterface $queuePublisher;

    /**
     * @param SerializerInterface $serializer
     * @param MailSaverInterface $mailSaver
     * @param MailConfig $mailConfig
     * @param QueuePublisherInterface $queuePublisher
     */
    public function __construct(
        SerializerInterface $serializer,
        MailSaverInterface $mailSaver,
        MailConfig $mailConfig,
        QueuePublisherInterface $queuePublisher
    ) {
        $this->serializer = $serializer;
        $this->mailSaver = $mailSaver;
        $this->mailConfig = $mailConfig;
        $this->queuePublisher = $queuePublisher;
    }

    /**
     * @param string $mailData
     * @param string $type
     *
     * @return Mail
     */
    public function createFromType(
        string $mailData,
        string $type
    ): Mail {
        try {
            /** @var Mail $mail */
            $mail = $this->serializer->deserialize(
                $mailData,
                Mail::class,
                $type,
                ['groups' => ['create']]
            );
        } catch (Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        foreach ($mail->getAdditionalRecipients() as $additionalRecipient) {
            $additionalRecipient->setMail($mail);
        }

        foreach ($mail->getContents() as $content) {
            $content->setMail($mail);
        }

        $mail = $this->mailSaver->save($mail);

        $encodedMessageBody = json_encode(['mail_id' => $mail->getId()]);

        if ($encodedMessageBody === false) {
            throw new JsonException('Error happened while processing mail. Please try again.');
        }

        $this->queuePublisher->publish(
            $this->mailConfig->getQueueName(),
            $this->mailConfig->getQueueExchangeName(),
            $encodedMessageBody
        );

        return $mail;
    }
}
