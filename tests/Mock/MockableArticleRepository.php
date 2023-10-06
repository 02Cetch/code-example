<?php

namespace App\Tests\Mock;

use App\Repository\ArticleRepository;

class MockableArticleRepository extends ArticleRepository
{
    public function getArticlesQueryByTagLink(string $tagLink): MockableQuery
    {
        return new MockableQuery();
    }
}
