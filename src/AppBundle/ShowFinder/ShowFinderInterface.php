<?php

namespace AppBundle\ShowFinder;

interface ShowFinderInterface
{
    /**
     * Returns a list of shows according to the query passed
     * @param string $query : The query typed by the user
     *
     * @return Array $result : The results got from the implementations of the finder
     */
    public function findByName($query);

    /**
     * Return the name of the implementation of the ShowFinderInterface
     *
     * @return String name
     */
    public function getName();
}