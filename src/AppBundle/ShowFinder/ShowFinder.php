<?php

namespace AppBundle\ShowFinder;

class ShowFinder
{
	private $finders;

	public function searchByName($query)
	{
		$results = [];

		foreach ($this->finders as $finder) {
			//$tmp[$finder->getName()] = $finder->findByName($query);

			$results = array_merge($results, $finder->findByName($query));

		}

		return $results;
	}

	public function addFinder(ShowFinderInterface $finder)
	{
		$this->finders[] = $finder;
	}
}