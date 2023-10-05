<?php

namespace App\Dto\Request\Settings;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SettingsUpdateItem
{
    public function __construct(
        #[NotBlank]
        #[NotNull]
        private int $id,
        #[NotBlank]
        #[NotNull]
        private string $value
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
