<?php

namespace AppBundle\Serializer\Listener;

use AppBundle\Entity\Show;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShowListener implements EventSubscriberInterface
{
	private $doctrine;

	private $tokenStorage;

	public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
	{
		$this->doctrine = $doctrine;
		$this->tokenStorage = $tokenStorage;
	}

	public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::PRE_DESERIALIZE,
                'method' => 'preDeserialize',
                'class' => 'AppBundle\\Entity\\Show',
                'format' => 'json'
            ]
        ];
    }

    public function preDeserialize(PreDeserializeEvent $event)
    {
    	//$event->setData(['dataSource' => Show::DATA_SOURCE_DB]);
    }
}