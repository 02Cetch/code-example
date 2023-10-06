<?php

namespace App\Dto\Response\Tag;

class TagsListItem
{
    public function __construct(private readonly string $title, private readonly int $quantity)
    {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
