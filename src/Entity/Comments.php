<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $contentCom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreateCom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActiveCom;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tricks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentCom(): ?string
    {
        return $this->contentCom;
    }

    public function setContentCom(string $contentCom): self
    {
        $this->contentCom = $contentCom;

        return $this;
    }

    public function getDateCreateCom(): ?\DateTimeInterface
    {
        return $this->dateCreateCom;
    }

    public function setDateCreateCom(\DateTimeInterface $dateCreateCom): self
    {
        $this->dateCreateCom = $dateCreateCom;

        return $this;
    }

    public function getIsActiveCom(): ?bool
    {
        return $this->isActiveCom;
    }

    public function setIsActiveCom(bool $isActiveCom): self
    {
        $this->isActiveCom = $isActiveCom;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTricks(): ?Tricks
    {
        return $this->tricks;
    }

    public function setTricks(?Tricks $tricks): self
    {
        $this->tricks = $tricks;

        return $this;
    }
}
