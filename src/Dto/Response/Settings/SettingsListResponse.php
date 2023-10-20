<?php

namespace App\Dto\Response\Settings;

class SettingsListResponse
{
    /**
     * @param SettingListItem[] $items
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
