<?php

namespace App\Controller;

use App\Entity\NotificationChannel;
use App\Entity\Site;
use App\Form\NotificationChannelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationChannelController extends AbstractController
{
    /**
     * @Route("/site/notification/channel/", name="site_notification_channel")
     */
    public function index(): Response
    {
        $user = $this->getUser()->getId();
        $notificationChannels = $this->getDoctrine()->getRepository(NotificationChannel::class)->findBy(['user' => $user]);
        return $this->render('notification_channel/index.html.twig', [
            'notificationChannels' => $notificationChannels
        ]);
    }

    /**
     * @Route("/site/new/notification/channel", name="site_new_notification_channel")
     * @Route("/site/edit/notification/channel/{id}", name="site_edit_notification_channel")
     */
    public function new(Request $request, $id = null): Response
    {
        $channel = new NotificationChannel();

        if ($id != null) {
            $channel = $this->getDoctrine()->getRepository(NotificationChannel::class)->find($id);
        }

        $form = $this->createForm(NotificationChannelType::class, $channel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $channel->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            if ($id == null) {
                $em->persist($channel);
            }
            $em->flush();
            return $this->redirectToRoute('site_notification_channel');
        }

        return $this->render('notification_channel/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/site/notification/delete/{id}", name="site_notification_delete")
     */
    public function delete($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository(NotificationChannel::class)->find($id);
        $em->remove($entry);
        $em->flush();
        return $this->redirectToRoute('site_notification_channel');
    }
}
