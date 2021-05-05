<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use App\Form\SiteTestType;
use App\Repository\SiteTestRepository;
use App\SiteTestEngine\SiteTestProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteTestController extends AbstractController
{
    /**
     * @Route("/site/advance/test/{id}", name="site_advance_test", methods={"GET"})
     */
    public function siteTest($id): Response
    {
        $siteTests = $this->getDoctrine()->getRepository(SiteTest::class)->findBy(['site' => $id]);
        return $this->render('site_test/index.html.twig', [
            'siteTests' => $siteTests,
            'id' => $id
        ]);

    }

    /**
     * @Route("/site/new/advance/test", name="site_new_advance_test")
     * @Route("/site/edit/advance/test/{id}", name="site_edit_advance_test")
     */
    public function newSiteTest(Request $request, $id = null)
    {
        $siteTest = new SiteTest();
        if ($id != null) {
            $siteTest = $this->getDoctrine()->getRepository(SiteTest::class)->find($id);
            $id = $siteTest->getSite()->getId();
        }
        $resultSiteTests = $this->getDoctrine()->getRepository(SiteTestResults::class)->findBy(['siteTest' => $siteTest], ['id' => 'DESC'], 10);

        $form = $this->createForm(SiteTestType::class, $siteTest, [
            'is_edit' => ($siteTest->getId() !== null),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            if ($id == null) {
                $em->persist($siteTest);
            }
            $em->flush();
        }

        return $this->render('site_test/Test.html.twig', [
            'form' => $form->createView(),
            'resultSiteTests' => $resultSiteTests,
            'id' => $id
        ]);
    }

    /**
     * @Route("/site/test/{id}/run", name="site_advance_test_manual")
     */
    public function runSiteTest(Request $request, $id, SiteTestProcessor $siteTestProcessor)
    {
        $siteTest = $this->getDoctrine()->getRepository(SiteTest::class)->find($id);
        try {
            $response = $siteTestProcessor->getResponseForSite($siteTest->getSite());
            $result = $siteTestProcessor->run($siteTest, $response);
        } catch (\Exception $e) {
            return $this->json($e->getMessage());
        }
        return new JsonResponse(['status' => $result->getResult()]);
    }
}
