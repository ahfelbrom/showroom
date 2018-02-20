<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//------------------------------------------------------------------------------

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

//------------------------------------------------------------------------------

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

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

    /**
     * @Route("/users", name="post")
     * @Method({"POST"})
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory)
    {
        $data = [
            'error' => true,
            'message' => 'Your user isn\'t valid'
        ];

        $deserializationContext = DeserializationContext::create();
        $user = $serializer->deserialize($request->getContent(), User::class, 'json', $deserializationContext->setGroups(['creation']));
        
        $errors = $validator->validate($user);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();

            $encoder = $encoderFactory->getEncoder($user);
            $hashedPassword = $encoder->encodePassword($user->getPassword(), null);

            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();
            
            $data['error'] = false;
            $data['message'] = 'your user has been successfully added';

            $json = $serializer->serialize($data, 'json');

            return new Response($json, Response::HTTP_CREATED, array(
                'Content-Type' => 'application\json'
            ));
        }
        $data['explication'] = $errors;
        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_BAD_REQUEST, array(
            'Content-Type' => 'application\json'
        ));
    }
}