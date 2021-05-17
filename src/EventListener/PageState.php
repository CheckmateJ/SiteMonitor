<?php


namespace App\EventListener;


use App\Entity\NotificationLog;
use App\Entity\SiteChecks;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class PageState
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
        if ($entity instanceof SiteChecks === false) {
            return;
        }

        $site = $entity->getSite();
        $siteNotificationChannels = $site->getNotificationChannels()->getValues();
//        $this->logger->log(LogLevel::DEBUG, 'SiteId ', ['siteId'=>$entity->getSite()->getSiteCheck()->last()->getId()]);
        if (!$entity->getSite()->getSiteCheck()->last() || $entity->getSite()->getSiteCheck()->last()->getResult() != $entity->getResult()) {
//            $this->logger->log(LogLevel::DEBUG, 'Trying to send notification for site ', ['site'=>$site->getId(), 'numberOfChannels' => count($siteNotificationChannels)]);
            foreach ($siteNotificationChannels as $siteNotificationChannel) {
                $notificationLog = new NotificationLog();
                $notificationLog->setSite($site);
                $notificationLog->setDetails($entity->getError() ? $entity->getError() : '');
                $notificationLog->setNotificationChannel($siteNotificationChannel);
                $this->entityManager->persist($notificationLog);
            }
        }
        $this->entityManager->flush();


    }
}