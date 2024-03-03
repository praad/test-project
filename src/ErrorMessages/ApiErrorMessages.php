<?php

declare(strict_types=1);

namespace App\ErrorMessages;

enum ApiErrorMessages: string
{
    case SERVER_ERROR = 'Server error';
    case RESOURCE_NOT_FOUND_ERROR = 'Resource not found';
    case USER_ALREADY_EXISTS = 'User already exists';
}