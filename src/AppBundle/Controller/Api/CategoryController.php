<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
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
 * @Route(name="api_category_")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="list")
     * @Method({"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of all the categories in database",
     * )
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns one category in Database",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the category to show"
     * )
     */
    public function detailsAction(SerializerInterface $serializer, Category $category)
    {
        $data = $serializer->serialize($category, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/categories/shows/{id}", name="find_all_shows")
     * @Method({"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of all the shows bound to the category found",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the category"
     * )
     */
    public function findAllShowsAction(SerializerInterface $serializer, Category $category)
    {
        $shows = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show')->findAllFromCategory($category->getId());

        $data = $serializer->serialize($shows, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/categories", name="post")
     * @Method({"POST"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="Return The message that confirms the creation of the category",
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Return The message that says the errors of the request",
     * )
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return The message that confirms the update of the category",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Return The message that shows your errors in your request",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the category to update"
     * )
     * @SWG\Parameter(
     *     name="category",
     *     in="body",
     *     type="Category",
     *     description="The changes of the category to update",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Category::class, groups={"full"})
     *     )
     * )
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

    /**
     * @Route("/categories/{id}", name="delete")
     * @Method({"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return The message that confirms the update of the category",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="The message if the category isn't found",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of the category to delete"
     * )
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