<?php

namespace AppBundle\ShowFinder;


class ShowFinder
{
    private $finders;
    
    public function searchByName($query)
    {
        $founds = [];

        foreach ($this->finders as $finder) {
            $founds = array_merge($founds, $finder->findByName($query));
        }

        return $founds;
    }

    public function addFinder(ShowFinderInterface $finder)
    {
        $this->finders[] = $finder;
    }
}