<?php

namespace App\Shared\Infrastructure\Symfony\Listener;

use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: ExceptionEvent::class, method: 'handleException')]
class ValidationErrorListener
{
    public function __construct(#[Autowire(param: 'kernel.environment')] private string $env)
    {
    }

    public function handleException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            $this->handleHttpException($event, $exception);

            return;
        }

        if ($exception instanceof DomainException) {
            $this->handleDomainException($event, $exception);

            return;
        }

        if ('dev' !== $this->env) {
            $this->handleTechnicalException($event, $exception);
        }
    }

    private function handleHttpException(ExceptionEvent $event, HttpException $exception): void
    {
        if (($validationException = $exception->getPrevious()) instanceof ValidationFailedException) {
            $errors = array_reduce(iterator_to_array($validationException->getViolations()), function (array $carry, ConstraintViolationInterface $item) {
                $carry[] = [
                    'property' => $item->getPropertyPath(),
                    'message' => $item->getMessage(),
                ];

                return $carry;
            }, []);

            $event->setResponse(new JsonResponse(['errors' => $errors], $exception->getStatusCode()));

            return;
        }

        if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
            $event->setResponse(new JsonResponse(['error' => $exception->getMessage()], $exception->getStatusCode()));
        }
    }

    private function handleDomainException(ExceptionEvent $event, DomainException $exception): void
    {
        $event->setResponse(new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_CONFLICT));
    }

    private function handleTechnicalException(ExceptionEvent $event, \Throwable $exception): void
    {
        $event->setResponse(new JsonResponse([$exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}
