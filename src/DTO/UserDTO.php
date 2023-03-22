<?php

namespace App\DTO;

use App\Entity\User;

class UserDTO
{
    private ?int $id = null;

    private ?string $email = null;

    private array $roles = [];

    private ?string $username = null;

    private ?string $plainPassword;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function transferTo(User $user):void
    {
        if(!is_null($email = $this->getEmail())){
            $user->setEmail($email);
        }
        if(!is_null($username = $this->getUsername())){
            $user->setUsername($username);
        }
        if(!is_null($roles = $this->getRoles())){
            $user->setRoles($roles);
        }
    }
}