<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Show;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

    /**
     * @Route("/shows", name="post")
     * @Method({"POST"})
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
/*
{
    "categoryName": "science-fiction"
    "name": "Doctor who",
    "abstract": "The best show of the universe concerning the sci-fi category",
    "country": "US",
    "releaseDate": "2018-03-10",
    "mainPicture": "https://vl-media.fr/wp-content/uploads/2017/12/matt-smith.jpg",
    "dataSource": "API"
}
*/        
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