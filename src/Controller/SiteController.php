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
    { $user = $this->getUser()->getId();
        $sites = $this->getDoctrine()->getRepository(Site::class)->findBy(['user'=>$user]);
        return $this->render('site/index.html.twig', [
            'sites' => $sites
        ]);
    }

    /**
     * @Route("/site/new", name="new_site")
     */
    public function newHost(Request $request): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $site = new Site();
            $form = $this->createForm(SiteType::class, $site);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $site = $form->getData();
                $em = $this->getDoctrine()->getManager();

                $em->persist($site);
                $em->flush();


                return $this->redirect($this->generateUrl('site'));
            }


            return $this->render('site/NewPage.html.twig', [
                'form' => $form->createView(),
            ]);
        }


    /**
     * @Route("/site/edit/{id}", name="site_edit")
     *
     */
    public function edit(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $site = new Site();
        $site = $this->getDoctrine()->getRepository(Site::class)->find($id);

        $siteCheck = new SiteChecks();
        $siteCheck = $this->getDoctrine()->getRepository(SiteChecks::class)->findBy(['site'=>$site],['id'=>'DESC'], 10);


        $form = $this->createForm(SiteType::class, $site, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('site');
        }


            return $this->render('site/edit.html.twig', [
                'form' => $form->createView(),
                'siteCheck' => $siteCheck
            ]);
        }

    /**
     * @Route("/site/delete/{id}", name="site_delete")
     *
     */
    public function delete($id)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $em = $this->getDoctrine()->getManager();
            $entry = $em->getRepository(Site::class)->find($id);

            $em->remove($entry);
            $em->flush();
            return $this->redirectToRoute('site');
        }




}
