<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSettingsController extends AbstractController
{
    /**
     * @Route("/site/user/settings/{id}", name="user_settings")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder,$id): Response
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $form = $this->createForm(ChangePasswordFormType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $em->flush();
        }



        return $this->render('user_settings/index.html.twig',[
        'form'=>$form->createView()
        ]);
    }
}
