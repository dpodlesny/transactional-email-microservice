<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Creator;

use App\Entity\Mail;
use App\Model\Mail\Api\Validator\MailValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var MailValidatorInterface
     */
    protected MailValidatorInterface $validator;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @param SerializerInterface $serializer
     * @param MailValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        SerializerInterface $serializer,
        MailValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->em = $em;
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

        if ($this->validator->validate($mail) === false) {
            throw new BadRequestHttpException($this->validator->getErrorMessage());
        }

        $this->em->persist($mail->getRecipient());

        foreach ($mail->getAdditionalRecipients() as $additionalRecipient) {
            $additionalRecipient->setMail($mail);
        }

        foreach ($mail->getContents() as $content) {
            $content->setMail($mail);
        }

        $this->em->persist($mail);
        $this->em->flush();
        $this->em->refresh($mail);

        return $mail;
    }
}
