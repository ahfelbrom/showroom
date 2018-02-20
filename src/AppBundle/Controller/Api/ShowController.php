<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//------------------------------------------------------------------------------

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

//------------------------------------------------------------------------------

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

//------------------------------------------------------------------------------

use AppBundle\Entity\Show;

//------------------------------------------------------------------------------


/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/shows", name="list")
     * @Method({"GET"})
     */
    public function listAction(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $shows = $em->getRepository('AppBundle:Show')->findAll();
        
        $serializationContext = SerializationContext::create();
        $data = $serializer->serialize($shows, 'json', $serializationContext->setGroups(['show']));

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/shows/{id}", name="details")
     * @Method({"GET"})
     */
    public function detailsAction(SerializerInterface $serializer, Show $show)
    {
        $serializationContext = SerializationContext::create();
        $data = $serializer->serialize($shows, 'json', $serializationContext->setGroups(['show']));

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }
}