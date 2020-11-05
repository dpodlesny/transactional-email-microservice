<?php
declare(strict_types=1);

namespace App\Model\Mail\Validator;

use App\Entity\Mail;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MailValidator implements MailValidatorInterface
{
    /**
     * @var string|null
     */
    protected ?string $errorMessage;

    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Mail $mail
     *
     * @return bool
     */
    public function validate(Mail $mail): bool
    {
        /** @var ConstraintViolationInterface[] $errors */
        $errors = $this->validator->validate($mail);

        if (count($errors) === 0) {
            return true;
        }

        $this->errorMessage = '';

        foreach ($errors as $violation) {
            $this->errorMessage .= $violation->getPropertyPath().': '.$violation->getMessage()."\n";
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
