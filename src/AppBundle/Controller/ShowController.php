<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Entity\Category;
use AppBundle\Form\Type\ShowType;
use AppBundle\File\FileUploader;
use AppBundle\ShowFinder\ShowFinder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;


/**
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/", name="list")
     *
     */
    public function listAction(Request $request, ShowFinder $showFinder)
    {
        throw new \Exception("Error Processing Request", 1);
        
        $showRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show');
        $session = $request->getSession();
        
        if ($session->has('query_search_shows')) {
            $querySearchShows = $session->get('query_search_shows');
            $shows = $showFinder->searchByName($querySearchShows);
            
            $request->getSession()->remove('query_search_shows');
        } else {
            $shows = $showRepository->findAll();
        }
        
        return $this->render('show/list.html.twig', array(
            'shows' => $shows,
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

        if ($form->isSubmitted() && $form->isValid()) {
            $generatedName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());
            $show->setMainPicture($generatedName);
            $show->setDataSource(Show::DATA_SOURCE_DB);
            $show->setAuthor($this->getUser());

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
        if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @Route("/show/search", name="search")
     * @Method({"POST"})
     */
    public function searchAction(Request $request)
    {
        $request->getSession()->set('query_search_shows', $request->request->get('name'));
        
        return $this->redirectToRoute('show_list');
    }

    /**
     * @Route("/show/delete", name="delete")
     * @Method({"POST"})
     */
    public function deleteAction(Request $request, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $em = $this->getDoctrine()->getManager();
        $showId = $request->request->get('show_id');

        $show = $em->getRepository('AppBundle:Show')->findOneById($showId);
        if (!$show)
        {
            throw new NotFoundHttpException(sprintf("There is no show with id : %d", $showId));
            
        }

        $csrfToken = new CsrfToken('delete_show', $request->request->get('csrf_token'));

        if ($csrfTokenManager->isTokenValid($csrfToken)) {
            $this->getUser()->removeShow($show);
            $em->remove($show);
            $em->flush();
            unlink(__DIR__."/../../../web/upload/".$show->getMainPicture());
            $this->addFlash('success', 'the show has been removed for ever :D');
        } else {
            $this->addFlash('error', 'The csrf token is not valid. Stopping deletion');
        }

        
        return $this->redirectToRoute("show_list");
    }
}