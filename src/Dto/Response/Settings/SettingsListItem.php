<?php

namespace App\Dto\Response\Settings;

class SettingsListItem
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $title,
        private readonly ?array $value,
        private readonly ?array $allowed_values,
        private readonly string $fieldHtml,
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array|null
     */
    public function getValue(): ?array
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getFieldHtml(): string
    {
        return $this->fieldHtml;
    }

    /**
     * @return array|null
     */
    public function getAllowedValues(): ?array
    {
        return $this->allowed_values;
    }
}
