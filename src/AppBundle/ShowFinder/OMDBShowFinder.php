<?php

namespace AppBundle\ShowFinder;

use GuzzleHttp\Client;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function findByName($query)
    {
        $response_shows = $this->client->get('/?apikey=f35ff428&type=series&t="'.$query.'"');
        $response_show_table = \GuzzleHttp\json_decode($response_shows->getBody(), true);
        $show = [];
        $show['name'] = $response_show_table['Title'];
        $show['abstract'] = $response_show_table['Plot'];
        $show['country'] = $response_show_table['Country'];
        $show['author'] = $response_show_table['Writer'];
        $show['releasedDate'] = $response_show_table['Released'];
        $show['mainPicture'] = $response_show_table['Poster'];

        return $show;
    }

    public function getName()
    {
        return "IMDB client";
    }
}