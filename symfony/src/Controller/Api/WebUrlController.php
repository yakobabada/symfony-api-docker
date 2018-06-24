<?php

namespace App\Controller\Api;

use App\Entity\WebUrl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/web-url")
 */
class WebUrlController extends ApiController
{
    /**
     * @Route("")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $webUrl = $this->deserialize($request, new WebUrl());

        $errors = $this->get('validator')->validate($webUrl);

        if (count($errors) > 0) {
            return $this->createErrorApiResponse($errors);
        }

        $this->getDoctrine()->getManager()->persist($webUrl);
        $this->getDoctrine()->getManager()->flush();
        $this->getDoctrine()->getManager()->refresh($webUrl);

        return $this->createApiResponse($webUrl);
    }

    /**
     * @Route("")
     * @Method("GET")
     */
    public function listAction()
    {
        $urls = $this->getDoctrine()->getRepository(WebUrl::class)->findAll();

        return $this->createApiResponse($urls);
    }

    /**
     * @Route("/{webUrl}")
     * @Method("GET")
     *
     * @param WebUrl $webUrl
     *
     * @return Response
     */
    public function getAction(WebUrl $webUrl)
    {
        return $this->createApiResponse($webUrl);
    }

    /**
     * @Route("/{webUrl}")
     * @Method("DELETE")
     *
     * @param WebUrl $webUrl
     *
     * @return Response
     */
    public function deleteAction(WebUrl $webUrl)
    {
        $this->getDoctrine()->getManager()->remove($webUrl);
        $this->getDoctrine()->getManager()->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}