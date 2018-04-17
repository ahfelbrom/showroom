<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Media;
use AppBundle\Type\MediaType;
use AppBundle\File\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
	 * @Route("/")
	 * @Method({"POST"})
	 */
	public function uploadAction(Request $request, FileUploader $fileUploader, RouterInterface $router)
	{
		$media = new Media();
		$media->setFile($request->files->get('file'));

		// Validate media object

		$generatedFileName = $fileUploader->upload($media->getFile(), time());

		$path = $this->container->getParameter('upload_directory_file').'/'.$generatedFileName;

		$baseUrl = $router->getContext()->getScheme().'://'.$router->getContext()->getHost().':'.$router->getContext()->getHttpPort();
        $media->setPath($baseUrl.$path);

        $em = $this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();

        return $this->returnResponse($media->getPath(), Response::HTTP_CREATED);
	}
}