<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostal = null;

    #[ORM\ManyToOne(inversedBy: 'villes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Departement $departement = null;

    /**
     * @var Collection<int, CartePostale>
     */
    #[ORM\OneToMany(targetEntity: CartePostale::class, mappedBy: 'ville')]
    private Collection $cartePostales;

    public function __construct()
    {
        $this->cartePostales = new ArrayCollection();
    }

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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $Departement): static
    {
        $this->departement = $Departement;

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
            $cartePostale->setVille($this);
        }

        return $this;
    }

    public function removeCartePostale(CartePostale $cartePostale): static
    {
        if ($this->cartePostales->removeElement($cartePostale)) {
            // set the owning side to null (unless already changed)
            if ($cartePostale->getVille() === $this) {
                $cartePostale->setVille(null);
            }
        }

        return $this;
    }
}
