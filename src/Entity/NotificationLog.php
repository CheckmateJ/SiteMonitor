<?php

namespace App\Entity;

use App\Repository\NotificationLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=NotificationLogRepository::class)
 */
class NotificationLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="notificationLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=NotificationChannel::class, inversedBy="notificationLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notificationChannel;

    /**
     * @ORM\ManyToOne(targetEntity=SiteTest::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $siteTest;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getNotificationChannel(): ?NotificationChannel
    {
        return $this->notificationChannel;
    }

    public function setNotificationChannel(?NotificationChannel $notificationChannel): self
    {
        $this->notificationChannel = $notificationChannel;

        return $this;
    }

    public function getSiteTest(): ?SiteTest
    {
        return $this->siteTest;
    }

    public function setSiteTest(?SiteTest $siteTest): self
    {
        $this->siteTest = $siteTest;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
