<?php

namespace AppBundle\Serializer\Handler;

use AppBundle\Entity\Show;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShowHandler implements SubscribingHandlerInterface
{
	private $doctrine;

	private $tokenStorage;

	public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
	{
		$this->doctrine = $doctrine;
		$this->tokenStorage = $tokenStorage;
	}


	public static function getSubscribingMethods()
	{
		return [
			[
				'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
				'format' => 'json',
				'type' => 'AppBundle\Entity\Show',
				'method' => 'deserialize'
			]
		];
	}

	public function deserialize(JsonDeserializationVisitor $visitor, $data)
	{
    	$show = new Show();
    	$show
    	   ->setName($data['name'])
    	   ->setAbstract($data['abstract'])
    	   ->setCountry($data['country'])
    	   ->setReleaseDate(new \Datetime($data['release_date']))
    	   ->setMainPicture($data['main_picture'])
    	;

    	$em = $this->doctrine->getManager();

    	if (!$category = $em->getRepository('AppBundle:Category')->findOneById($data['category']['id'])) {
    		throw new \LogicException('The Category does not exist');
    	}

    	$show->setCategory($category);

    	$user = $this->tokenStorage->getToken()->getUser();
    	$show->setAuthor($user);

		return $show;
	}
}