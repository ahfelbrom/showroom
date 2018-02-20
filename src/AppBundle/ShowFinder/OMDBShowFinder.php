<?php

namespace AppBundle\ShowFinder;

use GuzzleHttp\Client;

//------------------------------------------------------------------------------

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

//------------------------------------------------------------------------------

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use AppBundle\Entity\User;

//------------------------------------------------------------------------------


class OMDBShowFinder implements ShowFinderInterface
{
    private $client;

    private $apiKey;

    private $tokenStorage;

    public function __construct(Client $client, $apiKey, TokenStorage $tokenStorage)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->tokenStorage = $tokenStorage;
    }

    public function findByName($query)
    {
        $response_shows = $this->client->get('/?apikey='.$this->apiKey.'&type=series&t="'.$query.'"');
        $response_show_table = \GuzzleHttp\json_decode($response_shows->getBody(), true);

        if ($response_show_table['Response'] == 'False' && $response_show_table['Error'] == 'Series not found!') {
            return array();
        }
        return $this->convertToShow($response_show_table);
    }

    /**
     * Private function that converts a json to a show
     *
     * @param string $json : the json show
     *
     * @return Show $show
     */
    private function convertToShow($json)
    {
        $shows = [];
        $category = new Category();
        $category->setName($json['Genre']);

        $show = new Show();
        $show
            ->setName($json['Title'])
            ->setCategory($category)
            ->setAuthor($this->tokenStorage->getToken()->getUser())
            ->setDataSource(Show::DATA_SOURCE_OMDB);
        if ($json['Plot'] = "N/A")
            $show->setAbstract("not provided");
        else
            $show->setAbstract($json['Plot']);
        $show->setReleaseDate(new \DateTime($json['Released']))
            ->setMainPicture($json['Poster']);

        $shows[] = $show;

        return $shows;
    }

    public function getName()
    {
        return "IMDB client";
    }
}