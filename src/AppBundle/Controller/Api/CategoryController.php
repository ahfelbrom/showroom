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

    /**
     * @Route("/categories", name="post")
     * @Method({"POST"})
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = [
            'error' => true,
            'message' => 'Your category isn\'t valid'
        ];
        
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json');
        
        $errors = $validator->validate($category);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $data['error'] = false;
            $data['message'] = 'your category has been successfully added';

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
     * @Route("/categories/{id}", name="put")
     * @Method({"PUT"})
     */
    public function putAction(Category $category, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
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
}