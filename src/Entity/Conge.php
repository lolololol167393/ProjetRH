<?php

namespace App\Entity;

use App\Repository\CongeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CongeRepository::class)]
class Conge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_fin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_reponse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_demande = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire_demandeur = null;

    #[ORM\Column(length: 255)]
    private ?string $decision = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'mesDemandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $demandeur = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'congesAValider')]
    private ?User $valideur = null;

    #[ORM\ManyToOne(inversedBy: 'conges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeConges $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTime $date_debut): static
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTime $date_fin): static
    {
        $this->date_fin = $date_fin;
        return $this;
    }

    public function getDateReponse(): ?\DateTime
    {
        return $this->date_reponse;
    }

    public function setDateReponse(?\DateTime $date_reponse): static
    {
        $this->date_reponse = $date_reponse;
        return $this;
    }

    public function getDateDemande(): ?\DateTime
    {
        return $this->date_demande;
    }

    public function setDateDemande(\DateTime $date_demande): static
    {
        $this->date_demande = $date_demande;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getCommentaireDemandeur(): ?string
    {
        return $this->commentaire_demandeur;
    }

    public function setCommentaireDemandeur(?string $commentaire_demandeur): static
    {
        $this->commentaire_demandeur = $commentaire_demandeur;
        return $this;
    }

    public function getDecision(): ?string
    {
        return $this->decision;
    }

    public function setDecision(string $decision): static
    {
        $this->decision = $decision;
        return $this;
    }

    public function getDemandeur(): ?User
    {
        return $this->demandeur;
    }

    public function setDemandeur(?User $demandeur): static
    {
        $this->demandeur = $demandeur;
        return $this;
    }

    public function getValideur(): ?User
    {
        return $this->valideur;
    }

    public function setValideur(?User $valideur): static
    {
        $this->valideur = $valideur;
        return $this;
    }

    public function getType(): ?TypeConges
    {
        return $this->type;
    }

    public function setType(?TypeConges $type): static
    {
        $this->type = $type;
        return $this;
    }
}