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

        $date = $entity->getCreatedAt();
        $dateForm = $date->format('Y-m-d H:i:s');

        $email = (new Email())
            ->from('123@gmail.com')
            ->to($entity->getNotificationChannel()->getDestination())
            ->subject('Page status')
            ->html('
            <html>
                <body>
                <h4>Site ' . $entity->getSite() . '</h4>
                    <p>Created at ' . $dateForm . '</p>' . $entity->getDetails() . ' 
				</body>
			</html>');

        $this->mailer->send($email);
    }
}