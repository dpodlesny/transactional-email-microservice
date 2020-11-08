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
     * @ORM\OneToOne(targetEntity=Recipient::class, cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     *
     * @var Recipient
     */
    private Recipient $recipient;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotNull()
     *
     * @var string
     */
    private string $subject;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\OneToMany(targetEntity=Recipient::class, mappedBy="mail", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @Assert\Valid()
     * @Assert\NotBlank()
     *
     * @var Collection<Recipient>
     */
    private Collection $additionalRecipients;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\OneToMany(targetEntity=Content::class, mappedBy="mail", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @Assert\Valid()
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Contents can't be empty",
     * )
     *
     * @var Collection<Content>
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

    public function __construct()
    {
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
     * @param Recipient $recipient
     *
     * @return Mail
     */
    public function setRecipient(Recipient $recipient): Mail
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return Mail
     */
    public function setSubject(string $subject): Mail
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return array<Recipient>
     */
    public function getAdditionalRecipients(): array
    {
        return $this->additionalRecipients->toArray();
    }

    /**
     * @param array<Recipient> $additionalRecipients
     *
     * @return Mail
     */
    public function setAdditionalRecipients(array $additionalRecipients): Mail
    {
        $this->additionalRecipients = new ArrayCollection($additionalRecipients);

        return $this;
    }

    /**
     * @return array<Content>
     */
    public function getContents(): array
    {
        return $this->contents->toArray();
    }

    /**
     * @param array<Content> $contents
     *
     * @return Mail
     */
    public function setContents(array $contents): Mail
    {
        $this->contents = new ArrayCollection($contents);

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
     * @param DateTimeInterface $createdAt
     *
     * @return Mail
     */
    public function setCreatedAt(DateTimeInterface $createdAt): Mail
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getSentAt(): ?DateTimeInterface
    {
        return $this->sentAt;
    }

    /**
     * @param DateTimeInterface|null $sentAt
     *
     * @return Mail
     */
    public function setSentAt(?DateTimeInterface $sentAt): Mail
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * @return Mail
     */
    public function markAsSentAt(): Mail
    {
        $this->sentAt = new DateTime();

        return $this;
    }

    /**
     * @param Recipient $recipient
     *
     * @return Mail
     */
    public function addAdditionalRecipient(Recipient $recipient): Mail
    {
        if (!$this->additionalRecipients->contains($recipient)) {
            $this->additionalRecipients[] = $recipient;
        }

        return $this;
    }

    /**
     * @param Content $content
     *
     * @return Mail
     */
    public function addContent(Content $content): Mail
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
        }

        return $this;
    }
}
