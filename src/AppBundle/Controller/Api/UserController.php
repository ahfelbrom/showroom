<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{
    /**
     * @Route("/users", name="list")
     * @Method({"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return all the users of the database",
     * )
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return The user found",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the user"
     * )
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
     *
     * @SWG\Response(
     *     response=201,
     *     description="Return The message that confirms the creation of the user",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Return The message that shows your errors in your request",
     * )
     * @SWG\Parameter(
     *     name="user",
     *     in="body",
     *     type="User",
     *     description="The show to persist in database",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=AppBundle\Entity\User::class, groups={"user"})
     *     )
     * )
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

    /**
     * @Route("/users/{id}", name="put")
     * @Method({"PUT"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return The message that confirms the update of the user",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Return The message that shows your errors in your request",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the user to update"
     * )
     * @SWG\Parameter(
     *     name="user",
     *     in="body",
     *     type="User",
     *     description="The user to update in database",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=AppBundle\Entity\User::class, groups={"user"})
     *     )
     * )
     */
    public function putAction(User $user, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory)
    {
        $data = [
            'error' => true,
            'message' => 'Your user isn\'t valid'
        ];
        
        $em = $this->getDoctrine()->getManager();
        $deserializationContext = DeserializationContext::create();
        // $serializedData = json_decode($request->getContent(), true);
        $newUser = $serializer->deserialize($request->getContent(), User::class, 'json');

        $encoder = $encoderFactory->getEncoder($user);
        $hashedPassword = $encoder->encodePassword($user->getPassword(), null);

        $newUser->setPassword($hashedPassword);
        if ($user->getUsername() == $newUser->getUsername())
        {
            $newUser->setUsername(null);
        }

        $errors = $validator->validate($newUser);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $user->update($newUser);
            
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your user has been successfully updated';

            $json = $serializer->serialize($data, 'json');

            return new Response($json, Response::HTTP_OK, array(
                'Content-Type' => 'application\json'
            ));
        }
        $data['explication'] = $errors;
        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_BAD_REQUEST, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/users/{id}", name="delete")
     * @Method({"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return The message that confirms the deletion of the user",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="The message if the user isn't found",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the user to delete"
     * )
     */
    public function deleteAction(Request $request, SerializerInterface $serializer)
    {
        $data = [
            'error' => true,
            'message' => 'The user hasn\'t been found'
        ];
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneById($request->get('id'));

        if ($user != null) {
            $em->remove($user);
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your user has been successfully deleted';

            $json = $serializer->serialize($data, 'json');

            return new Response($json, Response::HTTP_OK, array(
                'Content-Type' => 'application\json'
            ));
        }
        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_NOT_FOUND, array(
            'Content-Type' => 'application\json'
        ));
    }
}