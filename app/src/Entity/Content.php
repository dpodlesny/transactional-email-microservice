<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 */
class Content
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
     * @Assert\NotNull()
     * @Assert\Choice(callback={"App\Model\Mail\MailConfig", "getAllowedContentTypes"})
     *
     * @var string
     */
    private string $type;

    /**
     * @Groups({"api", "create"})
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotNull()
     *
     * @var string
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity=Mail::class, inversedBy="contents")
     *
     * @Assert\NotNull()
     *
     * @var Mail
     */
    private Mail $mail;

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Content
     */
    public function setType(string $type): Content
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Content
     */
    public function setContent(string $content): Content
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Mail
     */
    public function getMail(): Mail
    {
        return $this->mail;
    }

    /**
     * @param Mail $mail
     *
     * @return Content
     */
    public function setMail(Mail $mail): Content
    {
        $this->mail = $mail;

        return $this;
    }
}
