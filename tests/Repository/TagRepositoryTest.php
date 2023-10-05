<?php

namespace App\Tests\Repository;

use App\Repository\TagRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use App\Exception\Repository\NotFoundRepositoryException;

class TagRepositoryTest extends TestCase
{
    private readonly TagRepository $repository;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TagRepository::class);
        $this->faker = Factory::create('ru_RU');

        parent::setUp();
    }

    public function testFindTagsQuantityByUserId()
    {
        $userId = $this->faker->numberBetween(1, 100000);

        $this->repository->expects($this->once())
            ->method('findTagsQuantityByUserId')
            ->with($userId)
            ->willThrowException(new NotFoundRepositoryException("Теги для пользователя $userId не найдены"));

        $this->expectException(NotFoundRepositoryException::class);
        $this->expectExceptionMessage("Теги для пользователя $userId не найдены");

        $this->repository->findTagsQuantityByUserId($userId);
    }

    public function testGetMockTagsQuantity()
    {

        $this->repository->expects($this->once())
            ->method('getMockTagsQuantity')
            ->willReturn([]);

        $this->repository->getMockTagsQuantity();
    }
}
