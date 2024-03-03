<?php

declare(strict_types=1);

namespace App\Response;

use App\Dto\UserDto;

class ApiUsers extends BaseResponse
{
    /** @var array<int, UserDto> */
    private array $users = [];

    /** @return array<int, UserDto> */
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
}