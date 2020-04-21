<?php
namespace App\EventListener;

use App\Response\ApiResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;


class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (!$event->getThrowable() instanceof ApiResponseException) {
            return;
        }

        $response = new JsonResponse($event->getThrowable()->getApiResponse()->toArray());
        $response->setStatusCode($event->getThrowable()->getCode());

        $event->setResponse($response);
    }

    /*private function buildResponseData(Throwable $exception)
    {
        $messages = json_decode($exception->getMessage());

        if (!is_array($messages)) {
            $messages = $exception->getMessage() ? $exception->getMessage() : [];
        }

        return [
            "status"=> "error",
            'code' => $exception->getCode(),
            'message' => $messages,
            'error' => [
                'type' => Response::$statusTexts[$exception->getCode()],
            ]];
    }*/
}