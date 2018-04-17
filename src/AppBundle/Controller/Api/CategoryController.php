<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(name="api_category_")
 */
class CategoryController extends Controller
{
	/**
	 * @Method({"GET"})
	 * @Route("/categories", name="list")
	 */
	public function listAction(SerializerInterface $serializer)
	{
		$categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

		return $this->returnResponse($serializer->serialize($categories, 'json'), Response::HTTP_OK);
	}

	/**
	 * @Method({"GET"})
	 * @Route("/categories/{id}", name="get")
	 */
	public function getAction(Category $category, SerializerInterface $serializer)
	{
		return $this->returnResponse($serializer->serialize($category, 'json'), Response::HTTP_OK);
	}

	/**
	 * @Method({"POST"})
	 * @Route("/categories", name="create")
	 */
	public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
	{
		$category = $serializer->deserialize($request->getContent(), Category::class, 'json');

		$constraintViolationList = $validator->validate($category);

		if ($constraintViolationList->count() == 0) {
			$em = $this->getDoctrine()->getManager();

			$em->persist($category);
			$em->flush();

			return $this->returnResponse('Category created', Response::HTTP_CREATED);
		}

		return $this->returnResponse($serializer->serialize($constraintViolationList, 'json'), Response::HTTP_BAD_REQUEST);
	}

	/**
	 * Update a category.
	 *
	 * @Method({"PUT"})
	 * @Route("/categories/{id}", name="update")
	 */
	public function updateAction(Category $category, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
	{
        $newCategory = $serializer->deserialize($request->getContent(), Category::class, 'json');
        $constraintViolationList = $validator->validate($newCategory);

        if ($constraintViolationList->count() == 0) {
        	$category->update($newCategory);
			$this->getDoctrine()->getManager()->flush();

			return $this->returnResponse('Category updated', Response::HTTP_OK);
		}

		return $this->returnResponse($serializer->serialize($constraintViolationList, 'json'), Response::HTTP_BAD_REQUEST);
	}

	/**
	 * Delete a category.
	 *
	 * @Method({"DELETE"})
	 * @Route("/categories/{id}", name="delete")
	 */
	public function deleteACtion(Category $category)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($category);

		try {
			$em->flush();
		} catch(ForeignKeyConstraintViolationException $e) {

			return $this->returnResponse(sprintf('This category ("%s") is attached to at least one show. Make sure that this category is not attached to any show before deleting it.', $category->getName()), Response::HTTP_BAD_REQUEST);
		}

		return $this->returnResponse('', Response::HTTP_NO_CONTENT);
	}
}