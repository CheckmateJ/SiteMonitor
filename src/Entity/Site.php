<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 *
 */
class Site
{
    const frequencyKey = ["1m","5m","10m","15m","30m","1h","2h","4h","8h","12h","24h"];
    const frequencyValue = [1,5,10,15,30,60,120,240,480,720,1440];
    const statusKey= ['Enabled', 'Disabled'];
    const statusValue = [1,2];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Hostname(message="The server name must be a valid hostname.")
     */
    private $domainName;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $frequency;

    /**
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */

    private $updatedAt;

    /**
     * @ORM\OneToMany (targetEntity=SiteChecks::class, mappedBy="site", orphanRemoval=true)
     *
     */
    private $siteCheck;

    /**
     * @ORM\OneToMany(targetEntity=NotificationLog::class, mappedBy="site", orphanRemoval=true)
     */
    private $notificationLogs;

    /**
     * @ORM\OneToMany(targetEntity=SiteTestResults::class, mappedBy="site", orphanRemoval=true)
     */
    private $siteTestResult;

    /**
     * @ORM\ManyToMany(targetEntity=NotificationChannel::class, inversedBy="sites", orphanRemoval=true)
     */
    private $notificationChannels;

    /**
     * @ORM\OneToMany(targetEntity=SiteTest::class, mappedBy="site", orphanRemoval=true)
     */
    private $siteTest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDomainName(): ?string
    {
        return $this->domainName;
    }

    public function setDomainName(string $domainName): self
    {
        $this->domainName = $domainName;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(int $frequency): self
    {
        $this->frequency = $frequency;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __construct()
    {
        $this->siteCheck = new ArrayCollection();
        $this->siteTest = new ArrayCollection();
        $this->siteTestResult = new ArrayCollection();
        $this->notificationLogs = new ArrayCollection();
        $this->notificationChannels = new ArrayCollection();
    }

    /**
     * @return Collection|SiteChecks[]
     */
    public function getSiteCheck(): Collection
    {
        return $this->siteCheck;
    }
    /**
     * @return Collection|SiteTestResults[]
     */
    public function getSiteTestResult(): Collection
    {
        return $this->siteTestResult;
    }
    /**
     * @return Collection|SiteTest[]
     */
    public function getSiteTest(): Collection
    {
        return $this->siteTest;
    }

    public function __toString()
    {
        return (string)$this->getDomainName();
    }

    public function getRecentResponseTime()
    {
        $result = $this->getSiteCheck()->filter(function (SiteChecks $siteChecks) {

            if ($siteChecks->getCreatedAt() > new \DateTime('last day')) {
                return $siteChecks->getCreatedAt();
            }
        })->map(function (SiteChecks $siteCheck) {
            $now = new \DateTime();
            return [$now->diff($siteCheck->getCreatedAt())->h * 60 + $now->diff($siteCheck->getCreatedAt())->i, $siteCheck->getTimeServer()];
        });

        return $result;
    }

    /**
     * @return Collection|NotificationLog[]
     */
    public function getNotificationLogs(): Collection
    {
        return $this->notificationLogs;
    }

    public function addNotificationLog(NotificationLog $notificationLog): self
    {
        if (!$this->notificationLogs->contains($notificationLog)) {
            $this->notificationLogs[] = $notificationLog;
            $notificationLog->setSite($this);
        }

        return $this;
    }

    public function removeNotificationLog(NotificationLog $notificationLog): self
    {
        if ($this->notificationLogs->removeElement($notificationLog)) {
            // set the owning side to null (unless already changed)
            if ($notificationLog->getSite() === $this) {
                $notificationLog->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NotificationChannel[]
     */
    public function getNotificationChannels(): Collection
    {
        return $this->notificationChannels;
    }

    public function addNotificationChannel(NotificationChannel $notificationChannel): self
    {
        if (!$this->notificationChannels->contains($notificationChannel)) {
            $this->notificationChannels[] = $notificationChannel;
        }

        return $this;
    }

    public function removeNotificationChannel(NotificationChannel $notificationChannel): self
    {
        $this->notificationChannels->removeElement($notificationChannel);

        return $this;
    }

}

