<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Show;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{
	/**
	 * @Method({"GET"})
	 * @Route("/shows", name="list")
	 */
	public function listAction(SerializerInterface $serializer)
	{
		$shows = $this->getDoctrine()->getRepository('AppBundle:Show')->findAll();

		$serialzationContext = SerializationContext::create();

		return $this->returnResponse(
			$serializer->serialize($shows, 'json', $serialzationContext->setGroups(['show'])),
			Response::HTTP_OK
		);
	}

	/**
	 * @Method({"GET"})
	 * @Route("/shows/{id}", name="get")
	 */
	public function getAction(Show $show, SerializerInterface $serializer)
	{
		$serialzationContext = SerializationContext::create();

		return $this->returnResponse(
			$serializer->serialize($show, 'json', $serialzationContext->setGroups(['show'])), 
			Response::HTTP_OK
		);
	}

    /**
     * @Method({"POST"})
     * @Route("/shows", name="create")
     */
	public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
	{
		$serializationContext = DeserializationContext::create();

		try {
			$show = $serializer->deserialize($request->getContent(), Show::class, 'json');
		} catch(\LogicException $e) {
			return $this->returnResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
		}
		$show->setDataSource(Show::DATA_SOURCE_DB);

		$constraintViolationList = $validator->validate($show);
		if ($constraintViolationList->count() == 0) {
			$em = $this->getDoctrine()->getManager();
        	$em->persist($show);
        	$em->flush();

        	return $this->returnResponse('', Response::HTTP_CREATED);
		}

        return $this->returnResponse($serializer->serialize($constraintViolationList, 'json'), Response::HTTP_BAD_REQUEST);
	}

    /**
     * @Method({"PUT"})
     * @Route("/shows/{id}", name="update")
     */
	public function updateAction(Show $show, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
	{
		try {
			$newShow = $serializer->deserialize($request->getContent(), Show::class, 'json');
		} catch(\LogicException $e) {
			return $this->returnResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
		}

		$constraintViolationList = $validator->validate($newShow);
		if ($constraintViolationList->count() == 0) {

			$show->update($newShow);

			$em = $this->getDoctrine()->getManager();
        	$em->persist($show);
        	$em->flush();

        	return $this->returnResponse('', Response::HTTP_OK);
		}

        return $this->returnResponse($serializer->serialize($constraintViolationList, 'json'), Response::HTTP_BAD_REQUEST);
	}

    /**
     * @Method({"DELETE"})
     * @Route("/shows/{id}", name="delete")
     */
	public function deleteAction(Show $show)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($show);
		$em->flush();

		return $this->returnResponse('', Response::HTTP_NO_CONTENT);
	}
}