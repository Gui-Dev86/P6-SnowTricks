<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TricksRepository::class)
 */
class Tricks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleTrick;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $contentTrick;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreateTrick;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUpdateTrick;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActiveTrick;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="tricks", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="Tricks", orphanRemoval=true, cascade={"persist"})
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="Tricks", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainImage;

    public function __construct()
    {
        $this->upload = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->uploads = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleTrick(): ?string
    {
        return $this->titleTrick;
    }

    public function setTitleTrick(string $titleTrick): self
    {
        $this->titleTrick = $titleTrick;

        return $this;
    }

    public function getContentTrick(): ?string
    {
        return $this->contentTrick;
    }

    public function setContentTrick(string $contentTrick): self
    {
        $this->contentTrick = $contentTrick;

        return $this;
    }

    public function getDateCreateTrick(): ?\DateTimeInterface
    {
        return $this->dateCreateTrick;
    }

    public function setDateCreateTrick(\DateTimeInterface $dateCreateTrick): self
    {
        $this->dateCreateTrick = $dateCreateTrick;

        return $this;
    }

    public function getDateUpdateTrick(): ?\DateTimeInterface
    {
        return $this->dateUpdateTrick;
    }

    public function setDateUpdateTrick(?\DateTimeInterface $dateUpdateTrick): self
    {
        $this->dateUpdateTrick = $dateUpdateTrick;

        return $this;
    }

    public function getIsActiveTrick(): ?bool
    {
        return $this->isActiveTrick;
    }

    public function setIsActiveTrick(bool $isActiveTrick): self
    {
        $this->isActiveTrick = $isActiveTrick;

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

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    
    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTricks($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTricks() === $this) {
                $comment->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTricks($this);
        }

        return $this;
        
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTricks() === $this) {
                $video->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTricks($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getTricks() === $this) {
                $image->setTricks(null);
            }
        }

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }
}
