<?php

declare(strict_types=1);

namespace App\Dto;

class UsersDto extends BaseDto
{
    /** @var array<int, UserDto> */
    private array $users = [];

    /**
     * @return array<UserDto>
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array<int, UserDto> $users
     * @return $this
     */
    public function setUsers(array $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function addUser(UserDto $userDto): self
    {
        $this->users[] = $userDto;
        return $this;
    }
}