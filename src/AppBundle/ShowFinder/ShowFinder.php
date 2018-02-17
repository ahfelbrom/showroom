<?php

namespace AppBundle\ShowFinder;


class ShowFinder
{
    private $finders;
    
    public function searchByName($query)
    {
        $founds = [];

        foreach ($this->finders as $finder) {
            $founds[$finder->getName()] = $finder->findByName($query);
        }

        return $founds;
    }

    public function addFinder(ShowFinderInterface $finder)
    {
        $this->finders[] = $finder;
    }
}