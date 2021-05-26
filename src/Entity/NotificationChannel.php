<?php

namespace App\Entity;

use App\Repository\NotificationChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=NotificationChannelRepository::class)
 */
class NotificationChannel
{
    const type = ['email', 'slack'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notificationChannels")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destination;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity=Site::class, mappedBy="notificationChannels")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity=NotificationLog::class, mappedBy="NotificationChannel")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $notificationLogs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $defaultValue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notificationName;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
    }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

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

    /**
     * @return Collection|Site[]
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->addNotificationChannel($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->removeElement($site)) {
            $site->removeNotificationChannel($this);
        }

        return $this;
    }

    public function __toString()
    {
        if($this->notificationName){
            return $this->notificationName;
        }
        return $this->getType().' - '.$this->getDestination();
    }

    public function getDefaultValue(): ?bool
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(bool $defaultValue): self
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    public function getNotificationName(): ?string
    {
        return $this->notificationName;
    }

    public function setNotificationName(?string $notificationName): self
    {
        $this->notificationName = $notificationName;

        return $this;
    }
}
