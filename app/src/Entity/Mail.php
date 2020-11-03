<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\MailRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 */
class Mail
{
    /**
     * @Groups({"api"})
     *
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
     * @ORM\OneToOne(targetEntity=Recipient::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull()
     *
     * @var Recipient
     */
    private Recipient $recipient;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotNull()
     *
     * @var string
     */
    private string $subject;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\OneToMany(targetEntity=Recipient::class, mappedBy="mail")
     *
     * @var Recipient[]|Collection
     */
    private Collection $additionalRecipients;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\OneToMany(targetEntity=Content::class, mappedBy="mail")
     *
     * @var Content[]|Collection
     */
    private Collection $contents;

    /**
     * @Groups({"api"})
     *
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private DateTimeInterface $createdAt;

    /**
     * @Groups({"api"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var ?DateTimeInterface
     */
    private ?DateTimeInterface $sentAt;

    /**
     * @param Recipient $recipient
     * @param string $subject
     */
    public function __construct(Recipient $recipient, string $subject)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->additionalRecipients = new ArrayCollection();
        $this->contents = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Recipient
     */
    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return Collection|Recipient[]
     */
    public function getAdditionalRecipients(): Collection
    {
        return $this->additionalRecipients;
    }

    /**
     * @param Recipient $recipient
     *
     * @return Mail
     */
    public function addAdditionalRecipient(Recipient $recipient): self
    {
        if (!$this->additionalRecipients->contains($recipient)) {
            $this->additionalRecipients[] = $recipient;
        }

        return $this;
    }

    /**
     * @return Collection|Content[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    /**
     * @param Content $content
     *
     * @return Mail
     */
    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
        }

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getSentAt(): ?DateTimeInterface
    {
        return $this->sentAt;
    }

    /**
     * @return Mail
     */
    public function markAsSent(): Mail
    {
        $this->sentAt = new DateTime();

        return $this;
    }
}
