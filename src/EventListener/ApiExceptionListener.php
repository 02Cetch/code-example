<?php

namespace App\EventListener;

use App\Dto\Response\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: ExceptionEvent::class, priority: 10)]
class ApiExceptionListener
{
    private const HEADERS_ACCEPT_JSON = 'application/json';

    public function __construct(
        private ExceptionMappingResolver $resolver,
        private LoggerInterface $logger,
        private SerializerInterface $serializer
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->headers->get('Accept') == self::HEADERS_ACCEPT_JSON) {
            $throwable = $event->getThrowable();

            $mapping = $this->resolver->resolve($throwable::class);
            if (is_null($mapping)) {
                $mapping = ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $mapping->isLoggable()) {
                $this->logger->error($throwable->getMessage(), [
                    'trace' => $throwable->getTraceAsString(),
                    'previous' => $throwable->getPrevious()?->getMessage() ?? ''
                ]);
            }

            $message = $mapping->isHidden() ? Response::$statusTexts[$mapping->getCode()] : $throwable->getMessage();
            $data = $this->serializer->serialize(
                new ErrorResponse($message, $mapping->getCode()),
                JsonEncoder::FORMAT
            );

            $response = new JsonResponse($data, $mapping->getCode(), [], true);
            $event->setResponse($response);
        }
    }
}
