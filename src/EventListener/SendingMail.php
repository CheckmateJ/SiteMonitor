<?php


namespace App\EventListener;


use App\Entity\NotificationLog;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendingMail
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof NotificationLog === false) {
            return;
        }

        if ($entity->getNotificationChannel()->getType() == 'email') {
        $date = $entity->getCreatedAt();
        $dateForm = $date->format('Y-m-d H:i:s');

        $email = (new Email())
            ->to($entity->getNotificationChannel()->getDestination())
            ->subject('Page status')
            ->html('
            <html>
                <body>
                <h4>Domain: ' . $entity->getSite() . '</h4>
                    <p>Test: ' . $entity->getDetails() . ' 
                    <p>Type: ' . $entity->getSiteTest()->getType(). '</p>
				</body>
			</html>');

        $this->mailer->send($email);
        }
    }
}