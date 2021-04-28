<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * @UniqueEntity("domainName")
 */
class Site
{
    const frequency = [1, 5, 10, 15, 30, 60];
    const status = [1, 2];
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
     * @ORM\OneToMany (targetEntity=SiteChecks::class, mappedBy="site")
     *
     */
    private $siteCheck;

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
    }

    /**
     * @return Collection|SiteChecks[]
     */
    public function getSiteCheck(): Collection
    {
        return $this->siteCheck;
    }

    public function __toString()
    {
        return (string)$this->getDomainName();
    }

    public function getRecentResponseTime()
    {
        // filtrowanie zeby byly sitechecki z ostatnich 24 godzin ->filter
        $result = $this->getSiteCheck()->map(function (SiteChecks $siteCheck) {
            $now = new \DateTime();
            return [$now->diff($siteCheck->getCreatedAt())->h*60+$now->diff($siteCheck->getCreatedAt())->i, $siteCheck->getTimeServer()];
        });
        dump($result);
        return $result;
    }

    public function getTimeCheckFromTheLastTwentyFourHour()
    {
        $result = $this->getSiteCheck()->map(function (SiteChecks $siteCheck) {
            return $siteCheck->getCreatedAt();
        });

        return $result;
    }

}

