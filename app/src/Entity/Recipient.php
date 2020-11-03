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
     * @ORM\ManyToOne(targetEntity=Mail::class, inversedBy="recipients")
     *
     * @var Mail|null
     */
    private ?Mail $mail;

    /**
     * @param string $email
     * @param string $name
     * @param Mail|null $mail
     */
    public function __construct(string $email, string $name, ?Mail $mail)
    {
        $this->email = $email;
        $this->name = $name;
        $this->mail = $mail;
    }

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Mail|null
     */
    public function getMail(): ?Mail
    {
        return $this->mail;
    }
}
