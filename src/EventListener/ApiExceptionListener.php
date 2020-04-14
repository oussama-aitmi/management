<?php
namespace App\EventListener;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;


class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {


        if (!$event->getThrowable() instanceof Exception) {
            return;
        }
        //die($event->getThrowable()->getCode());
        $response = new JsonResponse($this->buildResponseData($event->getThrowable()));
        $response->setStatusCode($event->getThrowable()->getCode());

        $event->setResponse($response);
    }

    private function buildResponseData(Throwable $exception)
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
                //'type' => Response::$statusTexts[$exception->getCode()],
            ]];
    }
}