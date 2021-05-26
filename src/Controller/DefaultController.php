<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\SiteChecks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        $siteChecks = $this->getDoctrine()->getRepository(SiteChecks::class)->findAll();

        $allSites = $this->getDoctrine()->getRepository(Site::class)->findAll();

        return $this->render('default/index.html.twig',[
            'allSites' => $allSites,
            'SiteChecks' => $siteChecks,
        ]);
    }

}
