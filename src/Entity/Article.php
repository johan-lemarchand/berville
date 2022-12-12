<?php

namespace App\Entity;

use App\Repository\ArticleRepository;

use Cocur\Slugify\Slugify;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(length: 255)]
    private ?string $title;

    #[ORM\Column(type: 'text')]
    private ?string $content;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $slug;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'article')]
    private ?User $user;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'article_tag')]
    private $tag;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Images::class, cascade: ['persist'])]
    private $images;

    #[ORM\OneToMany(mappedBy: 'mainArticle', targetEntity: Images::class, cascade: ['persist'])]
    private $mainImage;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tag = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->mainImage = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * @return Collection
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
            $tag->addArticle($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->removeElement($tag)) {
            $tag->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMainImage(): Collection
    {
        return $this->mainImage;
    }

    public function addMainImage(Images $mainImage): self
    {
        if (!$this->mainImage->contains($mainImage)) {
            $this->mainImage[] = $mainImage;
            $mainImage->setMainArticle($this);
        }
        return $this;
    }

    public function removeMainImage(Images $mainImage): self
    {
        if ($this->mainImage->contains($mainImage)) {
            $this->mainImage->removeElement($mainImage);
            // set the owning side to null (unless already changed)
            if ($mainImage->getMainArticle() === $this) {
                $mainImage->setMainArticle(null);
            }
        }
        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->slug = (new Slugify())->slugify($this->title);
    }
}
