<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(max: 100, maxMessage: 'Le nom ne peut pas dépasser 100 caractères')]
    private ?string $nom = null;

    /**
     * @var Collection<int, departement>
     */
    #[ORM\OneToMany(targetEntity: Departement::class, mappedBy: 'region', cascade: ['remove'])]
    private Collection $departements;

    /**
     * @var Collection<int, cartePostale>
     */
    #[ORM\OneToMany(targetEntity: CartePostale::class, mappedBy: 'region')]
    private Collection $cartePostales;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: 'Le code est obligatoire')]
    #[Assert\Length(max: 10, maxMessage: 'Le code ne peut pas dépasser 10 caractères')]
    private ?string $code = null;

#[ORM\Column(type: 'text', nullable: true)]
private ?string $description = null;

    // Constructeur
    public function __construct()
    {
        $this->departements = new ArrayCollection();
        $this->cartePostales = new ArrayCollection();
    }

    // Getter & Setter

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Departement>
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): static
    {
        if (!$this->departements->contains($departement)) {
            $this->departements->add($departement);
            $departement->setRegion($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): static
    {
        if ($this->departements->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getRegion() === $this) {
                $departement->setRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CartePostale>
     */
    public function getCartePostales(): Collection
    {
        return $this->cartePostales;
    }

    public function addCartePostale(CartePostale $cartePostale): static
    {
        if (!$this->cartePostales->contains($cartePostale)) {
            $this->cartePostales->add($cartePostale);
            $cartePostale->setRegion($this);
        }

        return $this;
    }

    public function removeCartePostale(CartePostale $cartePostale): static
    {
        if ($this->cartePostales->removeElement($cartePostale)) {
            // set the owning side to null (unless already changed)
            if ($cartePostale->getRegion() === $this) {
                $cartePostale->setRegion(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = strtoupper(trim($code)); //force la majuscule

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
}
