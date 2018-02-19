<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

//------------------------------------------------------------------------------


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="app_login")
     *
     */
    public function loginAction(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            'error' => $error,
            'username' => $lastUsername
        ));
    }

    /**
     * @Route("/login_check", name="app_login_check")
     *
     */
    public function loginCheckAction(AuthenticationUtils $utils)
    {
        dump('this code will never be red');
    }
}