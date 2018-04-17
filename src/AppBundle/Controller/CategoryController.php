<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Type\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends Controller
{

    public function categoriesAction()
    {
        return $this->render('_includes/categories.html.twig', [
               'categories' => $this->getDoctrine()->getRepository('AppBundle:Category')->findAll()
        ]);
    }

    /**
	 * @Route("/create", name="create")
	 */
	public function createAction(Request $request)
	{
		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$em->persist($category);
			$em->flush();

			$this->addFlash('success', 'You successfully added a new category');

			return $this->redirectToRoute('show_list');
		}

		return $this->render('category/create.html.twig', ['categoryForm' => $form->createView()]);
	}
}