<?php

namespace App\DTO;

use App\Entity\User;


class UserDTO
{
    private ?int $id = null;

    private ?string $email = null;

    private array $roles = [];

    private ?string $username = null;

    private ?string $plainPassword = null;

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

    public function transferTo(User $user): void
    {
        $user->setEmail($this->getEmail());
        $user->setUsername($this->getUsername());
        $user->setRoles($this->getRoles());
    }

    public function transferFrom(User $user):void
    {
        $this->setEmail($user->getEmail());
        $this->setUsername($user->getUsername());
        $this->setRoles($user->getRoles());
    }
}