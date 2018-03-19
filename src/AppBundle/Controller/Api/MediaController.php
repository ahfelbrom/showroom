<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Media;
use AppBundle\File\FileUploader;
use AppBundle\Form\Type\MediaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * @Route("/media", name="media_")
 */
class MediaController extends Controller
{
    /**
     * @Route("/", name="upload")
     * @Method({"POST"})
     */
    public function uploadAction(Request $request, FileUploader $fileUploader, RouterInterface $router)
    {
        $media = new Media();

        $media->setFile($request->files->get('file'));
        $generatedName = $fileUploader->upload($media->getFile(), 'api');
        $path = substr($this->container->getParameter('upload_directory_file'), 1).'/'.$generatedName;

        $media->setPath($router->generate('show_list', array(), UrlGeneratorInterface::ABSOLUTE_URL).$path);
        
        $this->getDoctrine()->getManager()->persist($media);
        $this->getDoctrine()->getManager()->flush();

        return new Response($media->getPath(), Response::HTTP_CREATED);
    }
}