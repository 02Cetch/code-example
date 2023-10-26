<?php

namespace App\Dto\Request\Page;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SearchViewRequest
{
    public function __construct(
        #[NotNull]
        #[NotBlank]
        private readonly string $query
    ) {
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
