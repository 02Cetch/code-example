<?php

namespace App\Dto\Response\Setting;

class SettingsListResponse
{
    /**
     * @param SettingsListItem[] $items
     */
    public function __construct(private readonly array $items)
    {
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
