<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity]
#[ORM\Table(name: 'utilisateur', uniqueConstraints: [new ORM\UniqueConstraint(name: 'email', columns: ['email'])])]
class Utilisateur implements UserInterface,PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'nom', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 50)]
    #[Assert\Type('string')]
    #[Assert\Regex(pattern: '/^[a-zA-Z\s]+$/', message: 'The name must contain only letters and spaces.')]
    private ?string $nom = null;

    #[ORM\Column(name: 'prenom', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 50)]
    #[Assert\Type('string')]
    #[Assert\Regex(pattern: '/^[a-zA-Z\s]+$/', message: 'The name must contain only letters and spaces.')]
    private ?string $prenom = null;

    #[ORM\Column(name: 'email', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(name: 'password', type: 'string', length: 256, nullable: false)]
    private ?string $password = null;

    #[ORM\Column(name: 'telephone', type: 'string', length: 20, nullable: true)]
    #[Assert\Regex(pattern: '/^\d+$/', message: 'Le numéro de téléphone doit contenir uniquement des chiffres.')]
    #[Assert\Length(min: 8, max: 15)]
    private ?string $telephone = null;

    #[ORM\Column(name: 'image', type: 'string', length: 250, nullable: false, options: ['default' => 'default.jpg'])]
    private ?string $image = 'default.jpg';

    #[ORM\Column(name: 'adresse', type: 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 100)]
    #[Assert\Type('string')]
    private ?string $adresse = null;

    #[ORM\Column(name: 'role', type: 'string', length: 0, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    #[Assert\Type('string')]
    private ?string $role = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
    
        return $this;
    }
    
    

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function setUsername(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        if($this->role == 'Admin'){
            $roles = ['ROLE_Admin'];
        }else{
            $roles = ['ROLE_USER'];
        }

        return array_unique($roles);
    }

}