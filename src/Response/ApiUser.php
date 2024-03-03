<?php

declare(strict_types=1);

namespace App\Response;

use App\Dto\UserDto;

class ApiUserResponse extends BaseResponse
{
    private ?UserDto $user = null;

    public function getUser(): ?UserDto
    {
        return $this->user;
    }

    public function setUser(?UserDto $user): self
    {
        $this->user = $user;
        return $this;
    }
}