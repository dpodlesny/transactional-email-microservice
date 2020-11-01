<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotNull()
     * @Assert\Choice(callback={"App\Model\Mail\Config", "getAllowedContentTypes"})
     *
     * @var string
     */
    private string $type;

    /**
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
     * @param string $type
     * @param string $content
     * @param Mail $mail
     */
    public function __construct(string $type, string $content, Mail $mail)
    {
        $this->type = $type;
        $this->content = $content;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return Mail
     */
    public function getMail(): Mail
    {
        return $this->mail;
    }
}
