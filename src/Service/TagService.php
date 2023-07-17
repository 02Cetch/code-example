<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(private readonly TagRepository $tagRepository) {}

    public function getTopTags(int $limit = 5): array
    {
        return $this->tagRepository->findBy([], ['weight' => 'ASC'], 5);
    }

    public function getTagByLink(string $tagLink): Tag
    {
        return $this->tagRepository->findOneBy(['link' => $tagLink]);
    }
}
