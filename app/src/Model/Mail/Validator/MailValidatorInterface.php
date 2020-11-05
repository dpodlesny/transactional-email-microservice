<?php
declare(strict_types=1);

namespace App\Model\Mail\Validator;

use App\Entity\Mail;

interface MailValidatorInterface
{
    /**
     * Specification:
     *  - Validates Mail object.
     *  - Returns true if object is valid otherwise false.
     *
     * @param Mail $mail
     *
     * @return bool
     */
    public function validate(Mail $mail): bool;

    /**
     * Specification:
     *  - Returns string with error messages if object is invalid.
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string;
}
