<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\SiteChecks;
use App\Entity\User;
use App\Form\SiteEditType;
use App\Form\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site", name="site")
     */
    public function index(Request $request): Response
    {
        $sites = $this->getDoctrine()->getRepository(Site::class)->findBy(['user' => $this->getUser()]);
        return $this->render('site/index.html.twig', [
            'sites' => $sites
        ]);
    }

    /**
     * @Route("/site/new", name="site_new")
     * @Route("/site/edit/{id}", name="site_edit")
     *
     */
    public function edit(Request $request, $id = null)
    {
        $site = new Site();
        if ($id != null) {
            $site = $this->getDoctrine()->getRepository(Site::class)->find($id);
        }

        $siteId = $site->getId();
        $form = $this->createForm(SiteType::class, $site, [
            'is_edit' => ($site->getId() !== null),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($id == null) {
                $em->persist($site);
            }

            $em->flush();
        }

        return $this->render('site/edit.html.twig', [
            'form' => $form->createView(),
            'siteId' => $siteId
        ]);
    }

    /**
     * @Route("/site/delete/{id}", name="site_delete")
     *
     */
    public function delete($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository(Site::class)->find($id);

        $em->remove($entry);
        $em->flush();
        return $this->redirectToRoute('site');
    }

    /**
     * @Route("/site/checks/{id}", name="site_checks")
     * @Route("/site/checks", name="site_checks_empty")
     */
    public function siteChecks($id = null)
    {
        if($id != null) {
            $site = $this->getDoctrine()->getRepository(Site::class)->find($id);
            $siteCheck = $this->getDoctrine()->getRepository(SiteChecks::class)->findBy(['site' => $site], ['id' => 'DESC'], 10);
            $siteId = $site->getId();

            return $this->render('site/checks.html.twig', [
                'id' => $siteId,
                'siteCheck' => $siteCheck,
            ]);
        }
        return $this->redirectToRoute('site');

    }


}
