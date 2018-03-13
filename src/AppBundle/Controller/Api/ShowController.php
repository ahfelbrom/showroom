<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Show;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/shows", name="list")
     * @Method({"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return All the shows of the database",
     * )
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the json of the show found",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="The message sent if the show isn't found",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the show"
     * )
     */
    public function detailsAction(SerializerInterface $serializer, Show $show)
    {
        $serializationContext = SerializationContext::create();
        $data = $serializer->serialize($shows, 'json', $serializationContext->setGroups(['show']));

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/shows", name="post")
     * @Method({"POST"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="Return The message that confirms the creation of the show",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Return The message that shows your errors in your request",
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="body",
     *     type="Show",
     *     description="The show to persist in database",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Show::class, groups={"show"})
     *     )
     * )
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {     
        $data = [
            'error' => true,
            'message' => 'Your show isn\'t valid'
        ];
        
        $em = $this->getDoctrine()->getManager();
        $deserializationContext = DeserializationContext::create();
        $serializedData = json_decode($request->getContent(), true);
        $show = $serializer->deserialize($request->getContent(), Show::class, 'json');
        $show->setReleaseDate(new \DateTime($serializedData['releaseDate']));
        $category = $em->getRepository('AppBundle:Category')->findOneByName($serializedData['categoryName']);
        $author = $em->getRepository('AppBundle:User')->findOneByFullname('system');
        $show->setAuthor($author);
        $show->setCategory($category);
        $show->setMainPicture($serializedData['mainPicture']);
        $show->setDataSource(Show::DATA_SOURCE_DB);

        $errors = $validator->validate($show);

        if ($errors->count() == 0) {
            $em->persist($show);
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your show has been successfully added';

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
     * @Route("/shows/{id}", name="put")
     * @Method({"PUT"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="Return The message that confirms the update of the show",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Return The message that shows your errors in your request",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the show to update"
     * )
     * @SWG\Parameter(
     *     name="category",
     *     in="body",
     *     type="Show",
     *     description="The changes of the show to update",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Show::class, groups={"full"})
     *     )
     * )
     */
    public function putAction(Show $show, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = [
            'error' => true,
            'message' => 'Your show isn\'t valid'
        ];
        
        $em = $this->getDoctrine()->getManager();
        $deserializationContext = DeserializationContext::create();
        $serializedData = json_decode($request->getContent(), true);
        $newShow = $serializer->deserialize($request->getContent(), Show::class, 'json');
        $newShow->setReleaseDate(new \DateTime($serializedData['releaseDate']));
        $category = $em->getRepository('AppBundle:Category')->findOneByName($serializedData['category']['name']);
        $author = $em->getRepository('AppBundle:User')->findOneByFullname('system');
        $newShow->setAuthor($author);
        $newShow->setCategory($category);
        $newShow->setMainPicture($serializedData['mainPicture']);
        $newShow->setDataSource(Show::DATA_SOURCE_DB);
        $errors = $validator->validate($newShow);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $show->update($newShow);
            
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your show has been successfully updated';

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
     * @Route("/shows/{id}", name="delete")
     * @Method({"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return The message that confirms the deletion of the show",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Return The message if the show hasn't been found",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the show to delete"
     * )
     */
    public function deleteAction(Request $request, SerializerInterface $serializer)
    {
        $data = [
            'error' => true,
            'message' => 'The show hasn\'t been found'
        ];
        $em = $this->getDoctrine()->getManager();

        $show = $em->getRepository('AppBundle:Show')->findOneById($request->get('id'));

        if ($show != null) {
            $em->remove($show);
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your show has been successfully deleted';

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