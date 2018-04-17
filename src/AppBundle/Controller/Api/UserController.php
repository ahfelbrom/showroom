<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{
    /**
     * Get the list of users.
     *
     * @Method({"GET"})
     * @Route("/users", name="list")
     */
    public function listAction(SerializerInterface $serializer)
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        $serialzationContext = SerializationContext::create();

        return $this->returnResponse(
            $serializer->serialize($users, 'json', $serialzationContext->setGroups(['user'])),
            Response::HTTP_OK
        );
    }

    /**
     * Get a user by the id.
     *
     * @Method({"GET"})
     * @Route("/users/{id}", name="get")
     */
    public function getAction(User $user, SerializerInterface $serializer)
    {
        $serialzationContext = SerializationContext::create();

        return $this->returnResponse(
            $serializer->serialize($user, 'json', $serialzationContext->setGroups(['user'])),
            Response::HTTP_OK
        );
    }

    /**
     * Create a user.
     *
     * @Method({"POST"})
     * @Route("/users", name="create")
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory)
    {
        $serializationContext = DeserializationContext::create();
        $user = $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            $serializationContext->setGroups(['user_create', 'user'])
        );

        $constraintViolationList = $validator->validate($user, null, ['create']);

        if ($constraintViolationList->count() == 0) {
            $encoder = $encoderFactory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), null);
            $user->setPassword($password);

            $user->setRoles(explode($user->getRoles(), ', '));

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($user);
            $em->flush();

            return $this->returnResponse('User created.', Response::HTTP_CREATED);
        }

        return $this->returnResponse($serializer->serialize($constraintViolationList, 'json'), Response::HTTP_BAD_REQUEST); 
    }

    /**
     * Update a user.
     *
     * @Method({"PUT"})
     * @Route("/users/{id}", name="update")
     */
    public function updateAction(User $user, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory)
    {
        $serializationContext = DeserializationContext::create();
        $newUser = $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            $serializationContext->setGroups(['user_create', 'user'])
        );
        
        $constraintViolationList = $validator->validate($newUser, null, ['update']);

        if ($constraintViolationList->count() == 0) {
            $encoder = $encoderFactory->getEncoder($newUser);
            $password = $encoder->encodePassword($newUser->getPassword(), null);
            $newUser->setPassword($password);

            $user->update($newUser);

            $this->getDoctrine()->getManager()->flush();

            return $this->returnResponse('User successfully updated.', Response::HTTP_OK);
        }

        return $this->returnResponse($serializer->serialize($constraintViolationList, 'json'), Response::HTTP_BAD_REQUEST); 
    }

    /**
     * Delete a user.
     *
     * @Method({"DELETE"})
     * @Route("/users/{id}", name="delete")
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->returnResponse('', Response::HTTP_NO_CONTENT);
    }
}