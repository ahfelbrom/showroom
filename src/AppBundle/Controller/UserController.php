<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//------------------------------------------------------------------------------

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;

//------------------------------------------------------------------------------


/**
 * @Route("/user", name="user_")
 */
class UserController extends Controller
{
    /**
     * @Route("/create", name="create")
     *
     */
    public function createAction(Request $request)
    {
        $user = new User();
        
        $form = $this->createForm
        (
            UserType::class,
            $user,
            array
            ()
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'The user has been created');

            return $this->redirectToRoute('show_list');
        }

        return $this->render("user/create.html.twig", array(
            'form' => $form->createView()
        ));
    }
}