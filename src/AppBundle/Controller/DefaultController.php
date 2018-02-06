<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api")
 * @Method({"GET"})
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{username}",
     *      name="homepage",
     *      requirements={"username" = ".*"},
     *      schemes={"http", "https"}),
     * @Method({"GET"})
     */
    public function indexAction(Request $request, $username="")
    {
        return $this->render('default/index.html.twig', ['myVar' => $username]);
    }
}
