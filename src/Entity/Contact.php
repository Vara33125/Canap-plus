<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(
        max: 255,
        maxMessage: 'Le message ne doit pas depasser de {{ limit }} caractères',
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\Column]
    private ?bool $isGuest = null;

    #[ORM\ManyToOne(inversedBy: 'contact')]
    private ?User $Author = null;



    #[Assert\Length(
        max: 255,
        maxMessage: 'Le numéro de téléphone ne doit pas depasser de {{ limit }} caractères',
    )]
    #[Assert\NotBlank (message: "Le téléphone est obligatoire.")]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $visitor_phone = null;
    
    #[Assert\NotBlank (message: "L'email est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage:"L'email ne doit pas dépaser {{ limit }} caractères",
    )]
    #[Assert\Email(message: "L'email {{ value }} est invalide")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $visitor_email = null;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    

   

    public function isGuest(): ?bool
    {
        return $this->isGuest;
    }

    public function setGuest(bool $isGuest): static
    {
        $this->isGuest = $isGuest;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?UserInterface $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getVisitorPhone(): ?string
    {
        return $this->visitor_phone;
    }

    public function setVisitorPhone(?string $visitor_phone): static
    {
        $this->visitor_phone = $visitor_phone;

        return $this;
    }

    public function getVisitorEmail(): ?string
    {
        return $this->visitor_email;
    }

    public function setVisitorEmail(?string $visitor_email): static
    {
        $this->visitor_email = $visitor_email;

        return $this;
    }
}
