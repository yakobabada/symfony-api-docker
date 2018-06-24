<?php

namespace App\Controller\Web;

use App\Entity\WebUrl;
use App\Form\WebUrlType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/web-url")
 */
class WebUrlController extends Controller
{
    /**
     * @Route("", name="web_url")
     *
     * @return Response
     */
    public function indexAction()
    {
        $webUrls = $this->getDoctrine()->getRepository(WebUrl::class)->findAll();

        return $this->render('WebUrl/index.html.twig', [
            'webUrls' => $webUrls
        ]);
    }

    /**
     * @Route("/add", name="web_url_add")
     */
    public function addAction(Request $request)
    {
        $webUrl = new WebUrl();
        $form = $this->createForm(WebUrlType::class, $webUrl);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($webUrl);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('web_url');
        }

        return $this->render('WebUrl/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{webUrl}", name="web_url_delete")
     *
     * @param WebUrl $webUrl
     *
     * @return RedirectResponse
     */
    public function deleteAction(WebUrl $webUrl)
    {
        $this->getDoctrine()->getManager()->remove($webUrl);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('web_url');
    }
}