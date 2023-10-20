<?php

namespace App\Mapper;

use App\Dto\Response\Tag\TagsListItem;
use App\Exception\Service\BadInputServiceException;

class TagsMapper
{
    /**
     * @throws BadInputServiceException
     */
    public static function map(array $tagData, TagsListItem $dto): void
    {
        if (empty($tagData['title']) || empty($tagData['quantity'])) {
            throw new BadInputServiceException("Wrong tag data result");
        }

        $dto
            ->setTitle($tagData['title'])
            ->setQuantity($tagData['quantity']);
    }
}
