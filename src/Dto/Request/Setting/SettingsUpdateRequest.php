<?php

namespace App\Dto\Request\Setting;

class SettingsUpdateRequest
{
    /**
     * @var SettingsUpdateItem[] $items
     */
    private readonly array $items;

    public function __construct(mixed $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
