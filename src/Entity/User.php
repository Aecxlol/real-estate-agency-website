<?php

namespace App\Entity;

use Serializable;//Pour que l'utilisateur soit sauvegardé au niveau de la session
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    //--- FONCTION DE L'INTERFACE UserInterface ---//

    //fonction qui renvoit une liste des rôles du user
    public function getRoles()
    {
        return['ROLE_ADMIN'];
    }

    public function getSalt()
    {
        return null;
    }

    //supprimer des info qui seraient stockées dans l'entité et serait sensible
    public function eraseCredentials()
    {
        
    }

    //--- FONCTION DE L'INTERFACE Serializable ---//
    // pour que l'user soit sauvegardé au niveau de la session on doit use cet interface

    //transforme notre objet en chaine
    //on renvoit les infos qu'on souhaite conserver en session de notre user
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    //et inversement
    //si on veut générer un user à partir des infos ci-dessus
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
        
    }
}
