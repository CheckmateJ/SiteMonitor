<?php


namespace App\EventListener;


use App\Entity\NotificationLog;
use App\Entity\SiteChecks;
use App\Entity\SiteTestResults;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class AdvanceSiteTest
{
    private $entityManager;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof SiteTestResults === false) {
            return;
        }

        $site = $entity->getSite();
        $siteTest = $entity->getSiteTest();

        $siteNotificationChannels = $site->getNotificationChannels()->getValues();

        if (!$entity->getSiteTest()->getTestCheckResult()->last() || $entity->getSiteTest()->getTestCheckResult()->last()->getResult() != $entity->getResult()) {
            foreach ($siteNotificationChannels as $siteNotificationChannel) {
                $notificationLog = new NotificationLog();
                $notificationLog->setSite($site);
                $notificationLog->setDetails($entity->getResult() < 1 ?  'failed '. $entity->getDetails() : 'passed');
                $notificationLog->setNotificationChannel($siteNotificationChannel);
                $notificationLog->setSiteTest($siteTest);
                $this->entityManager->persist($notificationLog);
            }
        }
        $this->entityManager->flush();


    }
}