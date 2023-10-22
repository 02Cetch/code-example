<?php

namespace App\Mapper;

use App\Dto\Response\Tag\TagsListItem;
use App\Exception\Mapper\BadInputMapperException;

class TagMapper
{
    /**
     * @throws BadInputMapperException
     */
    public static function map(array $tagData, TagsListItem $dto): void
    {
        if (empty($tagData['title']) || empty($tagData['quantity'])) {
            throw new BadInputMapperException("Wrong tag data result");
        }

        $dto
            ->setTitle($tagData['title'])
            ->setQuantity($tagData['quantity']);
    }
}
