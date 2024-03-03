<?php

declare(strict_types=1);

namespace App\EventSubscribers;

use App\Services\ExceptionResponseBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class KernelSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private ExceptionResponseBuilder $exceptionResponseBuilder
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException']
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = $this->exceptionResponseBuilder->getExceptionResponse($exception);

        if ($response) {
            $event->setResponse($response);
        }
    }
}