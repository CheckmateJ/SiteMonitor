<?php

namespace App\Entity;

use App\Repository\SiteTestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SiteTestRepository::class)
 */
class SiteTest
{
    const Type = ['Keyword', 'Header', 'Required Texts', 'Ssl Expiration Test', 'Schema Test', 'Domain Expiration Test'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sitesCheck")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="json")
     */
    private $configuration = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $frequency;

    /**
     * @ORM\OneToMany (targetEntity=SiteTestResults::class, mappedBy="siteTest")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $testCheckResult;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $testName;

    /**
     * @ORM\OneToMany(targetEntity=NotificationLog::class, mappedBy="SiteTest")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $notificationLogs;


    public function __construct()
    {
        $this->testCheckResult = new ArrayCollection();
    }

    /**
     * @return Collection|SiteTestResults[]
     */
    public function getTestCheckResult(): Collection
    {
        return $this->testCheckResult;
    }

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): self
    {
        $this->configuration = $configuration;

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

    public function getTestName(): ?string
    {
        return $this->testName;
    }

    public function setTestName(?string $testName): self
    {
        $this->testName = $testName;

        return $this;
    }
    public function __toString()
    {
        return $this->getConfiguration();
    }
}
