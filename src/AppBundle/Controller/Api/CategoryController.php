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

use AppBundle\Entity\Category;

//------------------------------------------------------------------------------


/**
 * @Route(name="api_category_")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="list")
     * @Method({"GET"})
     */
    public function listAction(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        
        $data = $serializer->serialize($categories, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/categories/{id}", name="details")
     * @Method({"GET"})
     */
    public function detailsAction(SerializerInterface $serializer, Category $category)
    {
        $data = $serializer->serialize($category, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }
}