<?php

namespace App\Dto\Response\Settings;

class BaseSettingDetails
{
    private int $id;
    private string $name;
    private string $title;
    private ?array $value;
    private ?array $allowed_values;
    private string $fieldHtml;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getAllowedValues(): ?array
    {
        return $this->allowed_values;
    }

    public function setAllowedValues(?array $allowed_values): BaseSettingDetails
    {
        $this->allowed_values = $allowed_values;
        return $this;
    }

    public function getFieldHtml(): string
    {
        return $this->fieldHtml;
    }

    public function setFieldHtml(string $fieldHtml): self
    {
        $this->fieldHtml = $fieldHtml;
        return $this;
    }
}
