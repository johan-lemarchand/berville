<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Regex(pattern: '/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/')]
    #[Assert\Length(min: 10, max: 10, minMessage: 'Votre numéro de téléphone est incorrect', maxMessage: 'Votre numéro de téléphone est incorrect')]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 10, max: 255, minMessage: 'le nombre de caractère est trop minime', maxMessage: 'le nombre de caractère maximal est dépassé')]
    private ?string $address = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Images $logo = null;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLogo(): ?Images
    {
        return $this->logo;
    }

    public function setLogo(?Images $logo): self
    {
        $this->logo = $logo;

        return $this;
    }
}
