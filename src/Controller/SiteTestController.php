<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use App\Form\SiteTestType;
use App\SiteTestEngine\SiteTestProcessor;
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
        $siteTests = $id ? $this->getDoctrine()->getRepository(SiteTest::class)->findBy(['site' => $id]) : null;
        $site = $this->getDoctrine()->getRepository(Site::class)->find($id);
        $siteName = $site->getDomainName();

        return $this->render('site_test/index.html.twig', [
            'siteTests' => $siteTests,
            'id' => $id,
            'siteName'=> $siteName
        ]);
    }

    /**
     * @Route("/site/new/advance/test/{slug}", name="site_new_advance_test")
     * @Route("/site/edit/advance/test/{id}", name="site_edit_advance_test")
     */
    public function newSiteTest(Request $request, $id = null, $slug = null)
    {
        $siteTest = new SiteTest();
        $site = $slug ? $this->getDoctrine()->getRepository(Site::class)->find($slug) : null;

        $editSiteTest = $id ? $this->getDoctrine()->getRepository(SiteTest::class)->find($id) : null;
        $siteId = $id ? $editSiteTest->getSite() : $site;

        $resultSiteTests = $this->getDoctrine()->getRepository(SiteTestResults::class)->findBy(['siteTest' => $editSiteTest], ['id' => 'DESC'], 10);

        $form = $this->createForm(SiteTestType::class, $slug ? $siteTest : $editSiteTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            if ($id == null) {
                $siteTest->setSite($site);
                $em->persist($siteTest);
            } else {
                $editSiteTest->setSite($siteId);
            }
            $em->flush();

            return $this->redirect($this->generateUrl('site_advance_test', $slug ? ['id' => $slug] : ['id' => $siteId->getId()]));
        }

        return $this->render('site_test/Edit.html.twig', [
            'form' => $form->createView(),
            'resultSiteTests' => $resultSiteTests,
            'siteId' => $siteId->getId(),
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

    /**
     * @Route("/site/test/delete/{id}/{slug}", name="site_advance_test_delete")
     */
    public function delete($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository(SiteTest::class)->find($id);

        $em->remove($entry);
        $em->flush();
        return $this->redirect($this->generateUrl('site_advance_test', ['id' => $slug]));
    }
}
