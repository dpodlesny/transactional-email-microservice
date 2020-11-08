<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RecipientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecipientRepository::class)
 */
class Recipient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private int $id;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Email()
     * @Assert\NotNull()
     *
     * @var string
     */
    private string $email;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Name must be at least {{ limit }} characters long",
     *      maxMessage = "Name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     *
     * @var string
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Mail::class, inversedBy="additionalRecipients")
     *
     * @var Mail|null
     */
    private ?Mail $mail;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Recipient
     */
    public function setEmail(string $email): Recipient
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Recipient
     */
    public function setName(string $name): Recipient
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Mail|null
     */
    public function getMail(): ?Mail
    {
        return $this->mail;
    }

    /**
     * @param Mail|null $mail
     *
     * @return Recipient
     */
    public function setMail(?Mail $mail): Recipient
    {
        $this->mail = $mail;

        return $this;
    }
}
