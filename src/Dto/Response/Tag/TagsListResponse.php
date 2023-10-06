<?php

namespace App\Dto\Response\Tag;

class TagsListResponse
{
    /**
     * @param TagsListItem[] $items
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
