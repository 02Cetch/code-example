<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const DEFAULT_ROLE = 'ROLE_USER';
    public const WRITER_ROLE = 'ROLE_WRITER';
    public const ADMIN_ROLE = 'ROLE_ADMIN';

    public const ALLOWED_ROLES = [
        'user' => self::DEFAULT_ROLE,
        'writer' => self::WRITER_ROLE,
        'admin' => self::ADMIN_ROLE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nickname = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about_text = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $is_deleted = false;

    private ?string $plain_password = null;
    private ?string $virtual_role = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $job_title = null;

    #[ORM\ManyToMany(targetEntity: UserSkill::class, inversedBy: 'users')]
    private Collection $user_skills;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->user_skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (!$roles) {
            // guarantee every user at least has default role
            $roles[] = self::DEFAULT_ROLE;
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    public function setPlainPassword(string $password): static
    {
        $this->plain_password = $password;

        return $this;
    }

    public function getVirtualRole(): ?string
    {
        return $this->virtual_role;
    }

    public function setVirtualRole(string $role): static
    {
        $this->virtual_role = $role;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getAboutText(): ?string
    {
        return $this->about_text;
    }

    public function setAboutText(?string $about_text): static
    {
        $this->about_text = $about_text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isIsDeleted(): bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): static
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return array of unique tags
     */
    public function getTags(): array
    {
        $articles = $this->getArticles();

        $tags = [];
        $tagIds = [];

        foreach ($articles as $article) {
            $tags = $article->getTags()->toArray();

            foreach ($tags as $tag) {
                if (!in_array($tag->getId(), $tagIds)) {
                    $tags[] = $tag;
                    $tagIds[] = $tag->getId();
                }
            }
        }

        return $tags;
    }

    public function getJobTitle(): ?string
    {
        return $this->job_title;
    }

    public function setJobTitle(?string $job_title): static
    {
        $this->job_title = $job_title;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNickname();
    }

    /**
     * @return Collection<int, UserSkill>
     */
    public function getUserSkills(): Collection
    {
        return $this->user_skills;
    }

    public function addUserSkill(UserSkill $userSkill): static
    {
        if (!$this->user_skills->contains($userSkill)) {
            $this->user_skills->add($userSkill);
        }

        return $this;
    }

    public function removeUserSkill(UserSkill $userSkill): static
    {
        $this->user_skills->removeElement($userSkill);

        return $this;
    }
}
