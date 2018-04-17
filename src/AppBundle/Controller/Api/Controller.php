<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller extends BaseController
{
	public function returnResponse($data, $statusCode)
	{
		return new Response($data, $statusCode, ['Content-Type' => 'application/json']);
	}
}