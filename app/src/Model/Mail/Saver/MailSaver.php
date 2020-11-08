<?php

declare(strict_types=1);

namespace App\Model\Mail\Saver;

use App\Entity\Mail;
use App\Model\Mail\Validator\MailValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MailSaver implements MailSaverInterface
{
    /**
     * @var MailValidatorInterface
     */
    protected MailValidatorInterface $validator;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @param MailValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        MailValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $this->validator = $validator;
        $this->em = $em;
    }

    /**
     * @param Mail $mail
     *
     * @return Mail
     */
    public function save(Mail $mail): Mail
    {
        if ($this->validator->validate($mail) === false) {
            throw new BadRequestHttpException($this->validator->getErrorMessage());
        }

        $this->em->persist($mail->getRecipient());
        $this->em->persist($mail);
        $this->em->flush();
        $this->em->refresh($mail);

        return $mail;
    }
}
