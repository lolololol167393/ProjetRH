<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cette adresse email.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $type_contrat = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    /**
     * @var Collection<int, Conge>
     */
    #[ORM\OneToMany(targetEntity: Conge::class, mappedBy: 'demandeur')]
    private Collection $mesDemandes;

    /**
     * @var Collection<int, Conge>
     */
    #[ORM\OneToMany(targetEntity: Conge::class, mappedBy: 'valideur')]
    private Collection $congesAValider;

    public function __construct()
    {
        $this->mesDemandes = new ArrayCollection();
        $this->congesAValider = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(string $type_contrat): static
    {
        $this->type_contrat = $type_contrat;
        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function getRoles(): array
    {
        $roles = [];

        if ($this->role) {
            // On récupère la valeur technique (ex: ROLE_ADMIN)
            // Vérifiez si c'est getDroit() ou getNom() dans votre entité Role
            $roles[] = $this->role->getDroit(); 
        }

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    
    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Conge>
     */
    public function getMesDemandes(): Collection
    {
        return $this->mesDemandes;
    }

    /**
     * @return Collection<int, Conge>
     */
    public function getCongesAValider(): Collection
    {
        return $this->congesAValider;
    }

    public function __toString(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
    
    }
}