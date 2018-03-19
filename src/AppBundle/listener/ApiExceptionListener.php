<?php

namespace AppBundle\listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ApiExceptionListener implements EventSubscriberInterface
{
    const EXCEPTION_CODE = 'The server has a big problem';

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array('processExceptionForApi', 1)
        );
    }

    public function processExceptionForApi(GetResponseForExceptionEvent $event)
    {
        // TODO get the request and if the request's path begin with "/api" => faire ce qui suit
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        $api = substr($routeName, 0, 3);

        if (!$api === 'api')
        {
            return;
        }
        // get exception
        $data = array(
            'code' => self::EXCEPTION_CODE,
            'message' => $event->getException()->getMessage()
        );

        // construct a jsonresponse with a nice Message in the json format
        $response = new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);

        // set the response in the event
        $event->setResponse($response);

        // et zbim

        return $event;
    }
}