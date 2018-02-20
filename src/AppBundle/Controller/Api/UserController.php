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

use AppBundle\Entity\User;

//------------------------------------------------------------------------------


/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{
    /**
     * @Route("/users", name="list")
     * @Method({"GET"})
     */
    public function listAction(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        $serializationContext = SerializationContext::create();
        $data = $serializer->serialize($users, 'json', $serializationContext->setGroups(['user']));

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/users/{id}", name="details")
     * @Method({"GET"})
     */
    public function detailsAction(SerializerInterface $serializer, User $user)
    {
        $serializationContext = SerializationContext::create();
        $data = $serializer->serialize($user, 'json', $serializationContext->setGroups(['show']));

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }
}