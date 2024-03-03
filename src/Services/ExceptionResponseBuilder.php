<?php

declare(strict_types=1);

namespace App\Services;

use App\ErrorMessages\ApiErrorMessages;
use App\Response\ApiError;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;
use TypeError;

#[WithMonologChannel('app')]
readonly class ExceptionResponseBuilder
{

    public function __construct(
        private ValidationFailedErrorsBuilder $validationFailedErrorsBuilder,
        private SerializerInterface $serializer,
        private LoggerInterface $logger
    ) {
    }

    public function getExceptionResponse(mixed $exception): ?JsonResponse
    {
        return match (get_class($exception)) {
            ValidationFailedException::class => $this->getResponseForValidationFailedException($exception),
            UniqueConstraintViolationException::class => $this->getResponseForUniqueConstraintViolationException(),
            ResourceNotFoundException::class => $this->getResponseForResourceNotFoundException(),
            default => $this->getResponseForException($exception)
        };
    }

    private function getResponseForValidationFailedException(ValidationFailedException $exception): JsonResponse
    {
        $errors = $this->validationFailedErrorsBuilder->build($exception->getViolations());
        return new JsonResponse(
            $this->serializer->serialize((new ApiError())->setErrors($errors), 'json'),
            Response::HTTP_BAD_REQUEST, [], true
        );
    }

    private function getResponseForUniqueConstraintViolationException(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize((new ApiError())->addError(ApiErrorMessages::SERVER_ERROR->value), 'json'),
            Response::HTTP_BAD_REQUEST, [], true
        );
    }

    private function getResponseForResourceNotFoundException(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                (new ApiError())->addError(ApiErrorMessages::RESOURCE_NOT_FOUND_ERROR->value),
                'json'
            ),
            Response::HTTP_NOT_FOUND, [], true
        );
    }

    private function getResponseForException(Throwable $throwable): JsonResponse
    {
        $this->logger->error($throwable->getMessage());

        return new JsonResponse(
            $this->serializer->serialize((new ApiError())->addError(ApiErrorMessages::SERVER_ERROR->value), 'json'),
            Response::HTTP_INTERNAL_SERVER_ERROR, [], true
        );
    }
}