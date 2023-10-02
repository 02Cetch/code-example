<?php

namespace App\DataFixtures\Common;

use App\Entity\User;
use App\Factory\ArticleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class ArticleFixtures extends Fixture implements FixtureGroupInterface
{
    private const ARTICLE_QUANTITY = 50;

    private Generator $faker;

    public function __construct(
        private readonly ArticleFactory $factory,
        private readonly UserFactory $userFactory
    )
    {
        $this->faker = Factory::create('ru_RU');
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->userFactory->create(
            $this->faker->email,
            explode(' ', $this->faker->name)[0],
            '123'
        );
        $manager->persist($user);

        for ($i = 0; $i < self::ARTICLE_QUANTITY; $i++) {
            $dateTime = \DateTimeImmutable::createFromMutable($this->faker->dateTime);

            $article = $this->factory->create(
                $this->faker->realText(25),
                $this->faker->realText(4000),
                $dateTime
            );
            $article->setUser($user);

            $manager->persist($article);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['common'];
    }
}
