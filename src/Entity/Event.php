<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $place;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $picture;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\User', inversedBy: 'event')]
    private $user;

    #[ORM\Column(type: 'integer')]
    private $zip;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'float')]
    private $longitude;

    #[ORM\Column(type: 'float')]
    private $latitude;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: 'App\Entity\Images', cascade: ['persist'])]
    private $images;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'events')]
    private Collection $categorie;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->images = new ArrayCollection();
        $this->categorie = new ArrayCollection();
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
    /**
     * @return mixed
     */
    public function getDate(): mixed
    {
        return $this->date;
    }
    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }
    public function getPlace(): ?string
    {
        return $this->place;
    }
    public function setPlace(?string $place): self
    {
        $this->place = $place;

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
    public function getPicture(): ?string
    {
        return $this->picture;
    }
    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

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
    public function getZip(): ?int
    {
        return $this->zip;
    }
    public function setZip(int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

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
            $image->setEvent($this);
        }

        return $this;
    }
    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getEvent() === $this) {
                $image->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie->add($categorie);
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }
}
