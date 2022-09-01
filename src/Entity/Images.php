<?php
namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;
    #[ORM\ManyToOne(targetEntity: 'App\Entity\User', inversedBy: 'images')]
    private ?User $user;
    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'images')]
    private ?Article $article;
    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'mainImage')]
    private ?Article $mainArticle;
    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'images')]
    private ?Event $event;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;

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
    public function getArticle(): ?Article
    {
        return $this->article;
    }
    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
    public function getEvent(): ?Event
    {
        return $this->event;
    }
    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
    public function getMainArticle(): ?Article
    {
        return $this->mainArticle;
    }
    public function setMainArticle(?Article $mainArticle): self
    {
        $this->mainArticle = $mainArticle;

        return $this;
    }
}