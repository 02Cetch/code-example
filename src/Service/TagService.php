<?php

namespace App\Service;

use App\Repository\TagRepository;

class TagService
{
    public function __construct(private readonly TagRepository $tagRepository) {}
}
