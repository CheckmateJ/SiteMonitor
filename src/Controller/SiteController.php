<?php

namespace App\Controller;

use App\Entity\Site;
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

        $headline = ['Domain name', 'Status', 'User', 'Created at'];

        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $site = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($site);
            $em->flush();



            return $this->redirect($this->generateUrl('site'));
        }


        $sites = $this->getDoctrine()->getRepository(Site::class)->findTheLastTenRows();
        return $this->render('site/index.html.twig',[
            'form' => $form->createView(),
            'sites' => $sites,
            'headline' => $headline,
        ]);

    }
    /**
     * @Route("/site/edit/{id}", name="site_edit")
     *
     */
    public function edit(Request $request, $id)
    {
        $site = new Site();
        $site = $this->getDoctrine()->getRepository(Site::class)->find($id);


        $form = $this->createForm(SiteType::class, $site);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('site');
        }

        return $this->render('site/edit.html.twig', [
            'form' => $form->createView()
        ]);

//        $em = $this->getDoctrine()->getManager();
//        $entry = $em->getRepository(Site::class)->find($id);
//
//        $em->remove($entry);
//        $em->flush();
//        return $this->redirectToRoute('site');
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

}
