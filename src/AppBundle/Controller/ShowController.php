<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//------------------------------------------------------------------------------

use AppBundle\Form\Type\ShowType;

/**
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/", name="list")
     *
     */
    public function listAction(Request $request)
    {
        return $this->render('show/list.html.twig');
    }

    /**
     * @Route("/create", name="create")
     *
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm
        (
            ShowType::class,
            array
            ()
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            dump('ok');die;
        }

        return $this->render('show/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function categoriesAction(Request $request)
    {
        return $this->render(
            '_includes/categories.html.twig',
            ['categories' => ['Web design', 'HTML', 'Freebies', 'Javascript', 'CSS', 'Tutorials']
        ]);
    }
}