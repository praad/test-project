<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\User;
use BaseDto;

class UserDto extends BaseDto
{
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): UserDto
    {
        $this->user = $user;
        return $this;
    }


}