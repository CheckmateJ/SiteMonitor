<?php

namespace App\Controller;

use App\Entity\SiteTest;
use App\Form\SiteTestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteTestController extends AbstractController
{
    /**
     * @Route("/site/advance/test", name="site_advance_test")
     */
    public function siteTest(): Response
    {
        $siteTests = $this->getDoctrine()->getRepository(SiteTest::class)->findAll();
        return $this->render('site_test/index.html.twig',[
            'siteTests'=>$siteTests
        ]);
    }

    /**
     * @Route("/site/new/advance/test", name="site_new_advance_test")
     */
    public function newSiteTest(Request $request)
    {
        $siteTest = new SiteTest();

        $form = $this->createForm(SiteTestType::class, $siteTest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($siteTest);
            $em->flush();

            return $this->redirectToRoute('site_advance_test');
        }
        return $this->render('site_test/newAdvanceTest.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
