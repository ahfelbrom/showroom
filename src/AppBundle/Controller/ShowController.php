<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//------------------------------------------------------------------------------

use AppBundle\Entity\Show;
use AppBundle\Entity\Category;
use AppBundle\Form\Type\ShowType;

//------------------------------------------------------------------------------


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
     * @Route("/show/create", name="create")
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $show = new Show();
        
        $form = $this->createForm
        (
            ShowType::class,
            $show,
            array
            ()
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $generatedName = time()."_".$show->getCategory()->getName().".".$show->getMainPicture()->guessClientExtension();
            $path = $this->getParameter('kernel.project_dir')."/web".$this->getParameter('upload_directory_file');

            $file = $show->getMainPicture()->move($path, $generatedName);
            $show->setMainPicture($generatedName);

            $em->persist($show);
            $em->flush();

            $this->addFlash('success', 'La série a bien été enregistrée');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('show/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function categoriesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        return $this->render(
            '_includes/categories.html.twig',
            [
                'categories' => $categories
            ]
        );
    }
}