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

        $test = json_encode($entity->getSiteTest()->getConfiguration());

        if ($entity->getNotificationChannel()->getType() == 'slack' && $entity->getSiteTest() != null) {

            $client = new Client();
            $slackUrl = $entity->getNotificationChannel()->getDestination();

            $client->post($slackUrl,
                [
                    'json' => [
                        'text' => $entity->getSiteTest()->getTestName() ? 'Domain: ' . $entity->getSite()->getDomainName() . $entity->getSiteTest()->getUrl() . "\r\n" . 'Test Name: ' . $entity->getSiteTest()->getTestName() . "\r\n" . 'Test: ' . $entity->getDetails() . "\r\n" . 'Type: ' . $entity->getSiteTest()->getType() : 'Domain: ' . $entity->getSite()->getDomainName() . $entity->getSiteTest()->getUrl() . "\r\n" . 'Test: ' . $entity->getDetails() . "\r\n" . 'Type: ' . $entity->getSiteTest()->getType()
                    ]
                ]);
        }
    }
}