<?php

namespace App\Entity;

use App\Repository\DiplomeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiplomeRepository::class)]
class Diplome
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    /**
     * @var Collection<int, ObtentionDiplome>
     */
    #[ORM\OneToMany(targetEntity: ObtentionDiplome::class, mappedBy: 'diplome', orphanRemoval: true)]
    private Collection $obtentionDiplomes;

    public function __construct()
    {
        $this->obtentionDiplomes = new ArrayCollection();
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

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection<int, ObtentionDiplome>
     */
    public function getObtentionDiplomes(): Collection
    {
        return $this->obtentionDiplomes;
    }

    public function addObtentionDiplome(ObtentionDiplome $obtentionDiplome): static
    {
        if (!$this->obtentionDiplomes->contains($obtentionDiplome)) {
            $this->obtentionDiplomes->add($obtentionDiplome);
            $obtentionDiplome->setDiplome($this);
        }

        return $this;
    }

    public function removeObtentionDiplome(ObtentionDiplome $obtentionDiplome): static
    {
        if ($this->obtentionDiplomes->removeElement($obtentionDiplome)) {
            // set the owning side to null (unless already changed)
            if ($obtentionDiplome->getDiplome() === $this) {
                $obtentionDiplome->setDiplome(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Diplôme sans nom';
    }
}
