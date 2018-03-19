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
        $em = $this->doctrine->getManager();
        $show = new Show();
        $show
            ->setName($data['name'])
            ->setAbstract($data['abstract'])
            ->setCountry($data['country'])
            ->setReleaseDate(new \DateTime($data['release_date']))
            ->setMainPicture($data['main_picture'])
            ->setDataSource(Show::DATA_SOURCE_DB);

        $em = $this->doctrine->getManager();

        if (!$category = $em->getRepository('AppBundle:Category')->findOneByName($data['category']['name']))
        {
            throw new \LogicException("The category doesn't exists");
        }

        $show->setCategory($category);

        $user = $this->tokenStorage->getToken()->getUser();
        $show->setAuthor($user);

        return $show;
    }
}