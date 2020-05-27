<?php


namespace App\EventSubscriber;

use App\Response\ApiResponseException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseSubscriber implements EventSubscriberInterface
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

    public function onKernelResponse(ResponseEvent $event)
    {
        if ( !in_array($event->getResponse()->getStatusCode(), ['200', '201', '202'])) {
            return false;
        }

        $content = $this->NormalizeFormat(
            $event->getResponse()->getContent(),
            $event->getResponse()->getStatusCode()
        );

        $response = new JsonResponse(
            $content,
            $event->getResponse()->getStatusCode()
        );

        $event->setResponse($response);
    }

    /**
     * @param     $response
     * @param int $code
     */
    private function NormalizeFormat($response, $code = Response::HTTP_OK)
    {
        array(
            'status' => 'success',
            'code'=> $code,
            'title' => Response::$statusTexts[$code],
            'data'=> json_decode( $response, true)
        );
    }

    public static function getSubscribedEvents()
    {

        return [
            //KernelEvents::RESPONSE => ['onKernelResponse'],
            KernelEvents::EXCEPTION => ['onKernelException']
        ];
    }
}