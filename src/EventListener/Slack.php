<?php


namespace App\EventListener;


use App\Entity\NotificationLog;
use App\Entity\SiteTest;
use App\SiteTestEngine\SiteTestProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use GuzzleHttp\Client;


class Slack
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof NotificationLog === false) {
            return;
        }



        if ($entity->getNotificationChannel()->getType() == 'slack' && $entity->getSiteTest() != null) {

            $client = new Client();
            $slackUrl = $entity->getNotificationChannel()->getDestination();

            $client->post($slackUrl,
                [
                    'json' => [
                        'text' =>  $entity->getSiteTest()->getTestName() ? $entity->getSiteTest()->getTestName()."\r\n" .$entity->getDetails()  :  $entity->getDetails(). "\r\n" . $entity->getSiteTest()->getUrl(). "\r\n". $entity->getSiteTest()->getConfiguration() . "\r\n" . $entity->getSiteTest()->getType()
                    ]
                ]);
        }
    }
}