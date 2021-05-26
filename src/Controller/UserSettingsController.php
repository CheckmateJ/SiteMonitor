<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSettingsController extends AbstractController
{
    /**
     * @Route("/site/user/settings/passwords_change/{id}", name="user_settings_password_change")
     */
    public function passwordChange(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id): Response
    {
        if($this->getUser()->getId() == $id) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);

            $form = $this->createForm(ChangePasswordFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('plainPassword')->getData()) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                }
                $em->flush();
            }

            return $this->render('user_settings/passwordChange.html.twig', [
                'form' => $form->createView()
            ]);
        }else{
            throw new \Exception('Invalid id of user');
        }
    }
}
