<?php

namespace App\Entity;

use App\Repository\CartePostaleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartePostaleRepository::class)]
class CartePostale
{
    public const LOGO_DIR = 'uploads/cartes';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $logoNom = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(length: 10)]
    private ?string $orientation = null;

    #[ORM\Column]
    private bool $enCaroussel = false;

    #[ORM\Column]
    private \DateTimeImmutable $dateCreation;

    #[ORM\ManyToOne(inversedBy: 'cartePostales')]
    private ?Ville $ville = null;

    #[ORM\ManyToOne(inversedBy: 'cartePostales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    #[ORM\ManyToOne(targetEntity: Departement::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Departement $departement = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
    }

    // Getter et Setter
    public function getLogoPath(): ?string
    {
        return $this->logoNom 
            ? self::LOGO_DIR . '/' . $this->logoNom 
            : null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLogoNom(): ?string
    {
        return $this->logoNom;
    }

    public function setLogoNom(string $logoNom): static
    {
        $this->logoNom = $logoNom;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(string $orientation): static
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function isEnCaroussel(): ?bool
    {
        return $this->enCaroussel;
    }

    public function setEnCaroussel(bool $enCaroussel): static
    {
        $this->enCaroussel = $enCaroussel;

        return $this;
    }

    public function getDateCreation(): \DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $Region): static
    {
        $this->region = $Region;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;
        return $this;
    }

}
