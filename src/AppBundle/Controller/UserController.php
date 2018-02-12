<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
    public function createAction()
    {
        return $this->render("user/create.html.twig");
    }
}