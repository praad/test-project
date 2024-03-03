<?php

declare(strict_types=1);

namespace App\Dto;

class UserRegistrationDto
{
    private ?string $email = null;

    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $password = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): UserRegistrationDto
    {
        $this->email = $email;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): UserRegistrationDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): UserRegistrationDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): UserRegistrationDto
    {
        $this->password = $password;
        return $this;
    }
}