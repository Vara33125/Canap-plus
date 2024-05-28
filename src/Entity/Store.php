<?php

namespace App\Entity;

use App\Repository\StoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: StoreRepository::class)]
class Store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'stores', fileNameProperty: 'picture')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 500)]
    private ?string $picture = null;

    #[Assert\Length(
        max: 255,
        maxMessage: 'L\'adresse ne doit pas depasser de {{ limit }} caractères',
    )]
    #[Assert\NotBlank (message: "L'adresse est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom de la ville ne doit pas depasser de {{ limit }} caractères',
    )]
    #[Assert\NotBlank (message: "Le nom de la ville est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Assert\NotBlank (message: "Le code postal est obligatoire.")]
    #[ORM\Column]
    private ?int $cp = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $map = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getMap(): ?string
    {
        return $this->map;
    }

    public function setMap(?string $map): static
    {
        $this->map = $map;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            
        }
    }
}
