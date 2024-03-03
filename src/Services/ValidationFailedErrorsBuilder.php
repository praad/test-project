<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedErrorsBuilder
{
    /**
     * @return array<string, string>
     */
    public function build(ConstraintViolationListInterface $list): array
    {
        $errors = [];
        foreach ($list as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}