<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\Type\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/category", name="category_")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="list")
     *
     */
    public function listAction(Request $request)
    {
        return $this->render('category/list.html.twig');
    }

    /**
     * @Route("/create", name="create")
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = new Category();
        
        $form = $this->createForm
        (
            CategoryType::class,
            $category,
            array
            ()
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bien été enregistrée');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('category/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
}