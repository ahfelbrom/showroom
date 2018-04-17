<?php

namespace AppBundle\ShowFinder;

interface ShowFinderInterface
{
	/**
	 * Returns an array of shows according to the querry passed.
	 *
	 * @param String $query the query typed by the user
	 *
	 * @return Array $results The results got from the implementation of the ShowFinder
	 */
	public function findByName($query);

	/**
	 * Returns the name of the implmentation of the ShowFinder
	 *
	 * @return String $name
	 */
	public function getName();
}