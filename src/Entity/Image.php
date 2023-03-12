<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\DBAL\Schema\Index;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Index(fields: ["path"], name: 'pathx')]
#[ORM\Index(fields: ["host"], name: 'hostx')]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $host = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $is_deleted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(string $is_deleted): self
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }
}
