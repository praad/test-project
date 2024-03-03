<?php

declare(strict_types=1);

namespace App\Response;

abstract class BaseResponse
{
    private bool $success = false;

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): self
    {
        $this->success = $success;
        return $this;
    }
}