<?php

namespace App\Entity;

use App\Repository\SiteChecksRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=SiteChecksRepository::class)
 */
class SiteChecks
{
    const STATUS_OK = 1;
    const STATUS_ERROR = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sitesCheck")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $result;

    /**
     * @ORM\Column(type="integer")
     */
    private $statusCode;

    /**
     * @ORM\Column(type="float")
     */
    private $timeServer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $certificate;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $error;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ssl_status;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(int $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getTimeServer(): ?float
    {
        return $this->timeServer;
    }

    public function setTimeServer(float $timeServer): self
    {
        $this->timeServer = $timeServer;

        return $this;
    }

    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    public function setCertificate(string $certificate): self
    {
        $this->certificate = $certificate;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function getSslStatus(): ?int
    {
        return $this->ssl_status;
    }

    public function setSslStatus(?int $ssl_status): self
    {
        $this->ssl_status = $ssl_status;

        return $this;
    }

}
