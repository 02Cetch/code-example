<?php

namespace App\Entity\Admin;

use App\Repository\Admin\SettingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    public const DEFAULT_WEIGHT = 500;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private array $value = [];

    #[ORM\Column(nullable: true)]
    private array $allowed_values = [];

    #[ORM\Column(options: ["default" => self::DEFAULT_WEIGHT])]
    private ?int $weight = self::DEFAULT_WEIGHT;

    #[ORM\ManyToOne(inversedBy: 'settings')]
    private ?SettingType $type = null;

    private ?string $fieldHtml = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function setValue(?array $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getAllowedValues(): array
    {
        return $this->allowed_values;
    }

    public function setAllowedValues(?array $allowed_values): static
    {
        $this->allowed_values = $allowed_values;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getType(): ?SettingType
    {
        return $this->type;
    }

    public function setType(?SettingType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFieldHtml(): ?string
    {
        return $this->fieldHtml;
    }

    /**
     * @param string|null $fieldHtml
     */
    public function setFieldHtml(?string $fieldHtml): void
    {
        $this->fieldHtml = $fieldHtml;
    }
}
