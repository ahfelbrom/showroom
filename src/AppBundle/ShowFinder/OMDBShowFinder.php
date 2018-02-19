<?php

namespace AppBundle\ShowFinder;

use GuzzleHttp\Client;

//------------------------------------------------------------------------------

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;

//------------------------------------------------------------------------------


class OMDBShowFinder implements ShowFinderInterface
{
    private $client;

    private $apiKey;

    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function findByName($query)
    {
        $response_shows = $this->client->get('/?apikey='.$this->apiKey.'&type=series&t="'.$query.'"');
        $response_show_table = \GuzzleHttp\json_decode($response_shows->getBody(), true);

        if ($response_show_table['response'] == 'False')
            return array();
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
            ->setAuthor('To do later')
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