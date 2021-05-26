<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/email", name="mailer")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('123@gmail.com')
            ->to('matej50@gmail.com')
            ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. Mailtrap’s Guide on How to Build HTML Email is live on our blog')
            ->html('<html>
<body>
		<p><br>Hey</br>
		Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
		<p><a href="/blog/build-html-email/">Mailtrap’s Guide on How to Build HTML Email</a> is live on our blog</p>
		<img src="cid:logo"> ... <img src="cid:new-cover-image">
				</body>
			</html>');


  $mailer->send($email);

        // …
      return new Response(
          'Email was sent'
      );
    }
}
