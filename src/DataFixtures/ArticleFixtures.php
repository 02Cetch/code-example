<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ArticleFixtures extends Fixture
{
    private const ARTICLE_QUANTITY = 50;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
    }

    public function load(ObjectManager $manager)
    {
        $articleFactory = new ArticleFactory();

        for ($i = 0; $i < self::ARTICLE_QUANTITY; $i++) {
            $dateTime = \DateTimeImmutable::createFromMutable($this->faker->dateTime);

            $article = $articleFactory->create(
                $this->faker->realText(25),
                $this->faker->realText(4000),
                $dateTime
            );
            $manager->persist($article);
        }
        $manager->flush();
    }
}
