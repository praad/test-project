<?php

declare(strict_types=1);

namespace App\Response;

class ApiError extends BaseResponse
{
    /** @var array<string, string> */
    private array $errors = [];

    /** @return array<string, string> */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(?string $error): self
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @param array<string, string> $errors
     * @return $this
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }
}