<?php

declare(strict_types=1);

namespace App\Model\Api\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Handles exceptions as json response if request came with Accept `application/json` header.
 */
class ExceptionListener
{
    private const TYPE_JSON = 'application/json';
    private const KEY_MESSAGE = 'message';

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (in_array(self::TYPE_JSON, $request->getAcceptableContentTypes())) {
            $response = $this->createApiResponse($exception);
            $event->setResponse($response);
        }
    }

    /**
     * @param Throwable $exception
     *
     * @return JsonResponse
     */
    protected function createApiResponse(Throwable $exception): JsonResponse
    {
        $statusCode = Response::HTTP_BAD_REQUEST;

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        return new JsonResponse([self::KEY_MESSAGE => $exception->getMessage()], $statusCode);
    }
}
