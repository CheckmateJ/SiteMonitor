<?php

namespace App\Controller;

use App\Entity\NotificationChannel;
use App\Entity\NotificationLog;
use App\Entity\Site;
use App\Entity\SiteChecks;
use App\Entity\SiteTest;
use App\Entity\User;
use App\Form\SiteEditType;
use App\Form\SiteType;
use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;

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
     */
    public function edit(Request $request, $id = null)
    {
        $site = new Site();
        if ($id != null) {
            $site = $this->getDoctrine()->getRepository(Site::class)->find($id);
        }

        $siteName = $site->getDomainName();

        $notificationChannel = $this->getDoctrine()->getRepository(NotificationChannel::class)->findBy(['defaultValue' => 1]);

        $user = $this->getUser();
        $siteForUser = $this->getDoctrine()->getRepository(Site::class)->findBy(['user' => $user->getId()]);
        $siteId = $site->getId();
        $form = $this->createForm(SiteType::class, $site, [
            'is_edit' => ($site->getId() !== null),
            'user_id' => $user,
            'notificationChannel' => $notificationChannel
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ((!in_array($site->getDomainName(), $siteForUser) && $id == null) || $id != null) {

                $em = $this->getDoctrine()->getManager();
                $site->setUser($user);
                if ($id == null) {
                    $em->persist($site);
                }
                $em->flush();
                return $this->redirectToRoute('site');
            }

            $this->addFlash('error', 'This webpage already exist');
        }

        return $this->render('site/edit.html.twig', [
            'form' => $form->createView(),
            'siteName' => $siteName,
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
        if ($id != null) {
            $siteCheck = $this->getDoctrine()->getRepository(SiteChecks::class)->findBy(['site' => $id], ['id' => 'DESC'], 10);
            $site = $this->getDoctrine()->getRepository(Site::class)->find($id);
            $siteName = $site->getDomainName();
            return $this->render('site/checks.html.twig', [
                'id' => $id,
                'siteCheck' => $siteCheck,
                'siteName' => $siteName
            ]);
        }
        return $this->redirectToRoute('site');

    }

}
