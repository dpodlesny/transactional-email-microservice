<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Creator;

use App\Entity\Mail;
use App\Model\Mail\Saver\MailSaverInterface;
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
     * @param SerializerInterface $serializer
     * @param MailSaverInterface $mailSaver
     */
    public function __construct(
        SerializerInterface $serializer,
        MailSaverInterface $mailSaver
    ) {
        $this->serializer = $serializer;
        $this->mailSaver = $mailSaver;
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

        return $this->mailSaver->save($mail);
    }
}
