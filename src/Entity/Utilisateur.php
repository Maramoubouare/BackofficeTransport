<?php

namespace App\Entity;

// src/Entity/User.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    private $username;

    #[ORM\Column(type: 'string', unique: true)]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    private $password;

    // Getters and setters ...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        // Retournez le mot de passe de l'utilisateur
        return $this->password; // Remplacez par le champ approprié dans votre entité
    }

    public function setPassword(string $hashedPassword): void
    {
        // Définissez le mot de passe haché pour l'utilisateur
        $this->password = $hashedPassword; // Remplacez par le champ approprié dans votre entité
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
    public function eraseCredentials(): void
    {
        // Implémentez ici la logique de suppression des informations sensibles si nécessaire
        // Par exemple, réinitialiser le mot de passe en texte clair
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        // Retournez ici l'identifiant unique de l'utilisateur
        return $this->username; // Supposons que 'username' est l'identifiant dans votre entité
    }
 
}
