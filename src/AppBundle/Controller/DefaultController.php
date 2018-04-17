<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/examples", schemes={"http", "https"}, methods={"GET"})
 */
class DefaultController extends Controller
{
    /**
     * @Route("/lol/pouet")
     * @Route(
     *     "/{username}",
     *     requirements= {"username"=".*"},
     *     schemes={"http", "https"},
     *     name="homepage"
     * )
     */
    public function indexAction(Request $request, $username ="")
    {
        //dump($request->query); die;

        /*$username = '';
        if ($request->query->has('username')) {
            $username = $request->query->get('username');
        }*/

        return new Response(
            $this->renderView('default/index.html.twig', [
                'myVar' => $username
            ]),
            Response::HTTP_NOT_FOUND
        );
    }
}
