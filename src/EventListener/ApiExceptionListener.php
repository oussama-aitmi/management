<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;


class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {

        if (!$event->getThrowable() instanceof \Exception) {
            return;
        }
        $response = new JsonResponse($this->buildResponseData($event->getThrowable()));
        $response->setStatusCode($event->getThrowable()->getCode());

        $event->setResponse($response);
    }

    private function buildResponseData(Throwable $exception)
    {
        $messages = json_decode($exception->getMessage());

        if (!is_array($messages)) {
            $messages = $exception->getMessage() ? [$exception->getMessage()] : [];
            $messages = $exception->getMessage() ? $exception->getMessage() : [];
        }

        return [
            'error' => [
                'code' => $exception->getCode(),
                'type' => Response::$statusTexts[$exception->getCode()],
                'messages' => $messages
            ]];
    }
}