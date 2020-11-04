<?php
declare(strict_types=1);

namespace App\Model\Mail\Api\Validator;

use App\Entity\Mail;

interface MailValidatorInterface
{
    /**
     * @param Mail $mail
     *
     * @return bool
     */
    public function validate(Mail $mail): bool;

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string;
}
