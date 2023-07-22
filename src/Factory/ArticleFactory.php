<?php

namespace App\Factory;

use App\Entity\Article;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ArticleFactory
{
    public function create(
        string $title,
        string $text,
        \DateTimeImmutable $dateCreate,
        string $slug = null,
        string $textShort = null
    ): Article {
        $article = new Article();
        $article->setTitle($title);

        if ($slug) {
            $article->setSlug($slug);
        } else {
            $slugger = new AsciiSlugger('ru_RU');
            $article->setSlug($slugger->slug($title)->lower() . "_" . uniqid());
        }

        $article->setText($text);
        if ($textShort) {
            $article->setTextShort($textShort);
        } else {
            $article->setTextShort(mb_substr($text, 0, 100));
        }

        $article->setCreatedAt($dateCreate);
        $article->setUpdatedAt($dateCreate);
        $article->setDeleted(false);

        return $article;
    }
}
