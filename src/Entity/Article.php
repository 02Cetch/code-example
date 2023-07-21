<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?Image $main_image = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?Image $cover_image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(length: 512)]
    private ?string $text_short = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $is_deleted = null;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    private Collection $tags;

    private ?string $main_image_path = null;

    private ?string $cover_image_path = null;

    #[ORM\Column]
    private ?int $min_read = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $meta_title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $meta_description = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getMainImagePath(): ?string
    {
        return $this->main_image_path;
    }

    public function setMainImagePath(?string $url): self
    {
        $this->main_image_path = $url;
        return $this;
    }

    public function setMainImage(?Image $mainImage): self
    {
        $this->main_image = $mainImage;

        return $this;
    }
    public function getMainImage(): ?Image
    {
        return $this->main_image;
    }

    public function getCoverImagePath(): ?string
    {
        return $this->cover_image_path;
    }

    public function setCoverImagePath(?string $url): self
    {
        $this->cover_image_path = $url;
        return $this;
    }

    public function getCoverImage(): ?Image
    {
        return $this->cover_image;
    }

    public function setCoverImage(?Image $coverImage): static
    {
        $this->cover_image = $coverImage;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTextShort(): ?string
    {
        return $this->text_short;
    }

    public function setTextShort(string $text_short): self
    {
        $this->text_short = $text_short;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->is_deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getMinRead(): ?int
    {
        return $this->min_read;
    }

    public function setMinRead(int $min_read): static
    {
        $this->min_read = $min_read;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function setMetaTitle(?string $meta_title): static
    {
        $this->meta_title = $meta_title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): static
    {
        $this->meta_description = $meta_description;

        return $this;
    }
}
