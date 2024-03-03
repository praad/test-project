<?php

declare(strict_types=1);

namespace App\Dto;

class UserDto extends BaseDto
{
    private ?int $id = null;

    private ?string $email = null;

    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $username = null;

    private ?string $fullName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): UserDto
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): UserDto
    {
        $this->email = $email;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): UserDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): UserDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): UserDto
    {
        $this->username = $username;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): UserDto
    {
        $this->fullName = $fullName;
        return $this;
    }
}