<?php

namespace App\Entity;

use App\Repository\ObtentionDiplomeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObtentionDiplomeRepository::class)]
class ObtentionDiplome
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateObtention = null;

    #[ORM\ManyToOne(inversedBy: 'diplome')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'obtentionDiplomes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Diplome $diplome = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateObtention(): ?\DateTime
    {
        return $this->dateObtention;
    }

    public function setDateObtention(\DateTime $dateObtention): static
    {
        $this->dateObtention = $dateObtention;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): static
    {
        $this->diplome = $diplome;

        return $this;
    }
}
