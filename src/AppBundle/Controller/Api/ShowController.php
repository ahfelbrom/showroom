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

    /**
     * @Route("/shows", name="post")
     * @Method({"POST"})
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = [
            'error' => true,
            'message' => 'Your show isn\'t valid'
        ];
        
        $show = $serializer->deserialize($request->getContent(), Show::class, 'json');
        dump($show);die;
        $errors = $validator->validate($show);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
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
            'message' => 'Your category isn\'t valid'
        ];
        
        $newCategory = $serializer->deserialize($request->getContent(), Category::class, 'json');
        
        $errors = $validator->validate($newCategory);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $category->update($newCategory);
            
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your category has been successfully updated';

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
            'message' => 'The id for the category is not valid'
        ];
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:Category')->findOneById($request->get('id'));

        if ($category != null) {
            $shows = $em->getRepository('AppBundle:Show')->findAllFromCategory($category->getId());
            if ($shows != null)
            {
                foreach ($shows as $show) {
                    $show->removeCategory();
                }
            }
            $em->remove($category);
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your category has been successfully deleted';

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