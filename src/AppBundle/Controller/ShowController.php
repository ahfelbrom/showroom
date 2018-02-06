<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//------------------------------------------------------------------------------

use AppBundle\Entity\Show;
use AppBundle\Entity\Category;
use AppBundle\Form\Type\ShowType;
use AppBundle\File\FileUploader;
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
        $shows = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show')->findAll();
        
        return $this->render('show/list.html.twig', array(
            'shows' => $shows
        ));
    }

    /**
     * @Route("/show/create", name="create")
     *
     */
    public function createAction(Request $request, FileUploader $fileUploader)
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
            $generatedName = $fileUploader->upload($show->getMainPicture(), $show->getCategory()->getName());
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

    /**
     * @Route("/show/update/{id}", name="update")
     */
    public function updateAction(Request $request, Show $show, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm
        (
            ShowType::class,
            $show,
            array
            (
                'validation_groups' => ['update']
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $generatedName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());
            $show->setMainPicture($generatedName);
            
            $em->persist($show);
            $em->flush();

            $this->addFlash('success', 'La série a bien été modifiée');

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