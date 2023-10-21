<?php

namespace App\Tests\Service;

use App\Exception\Service\NotFoundServiceException;
use App\Service\TagService;
use App\Tests\AbstractTestCase;
use Faker\Factory;
use Faker\Generator;

class TagServiceTest extends AbstractTestCase
{
    private readonly TagService $service;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create('ru_RU');
        $this->service = $this->createMock(TagService::class);

        parent::setUp();
    }

    public function testFindTagsQuantityByUserId()
    {
        $userId = $this->faker->numberBetween(1, 100000);

        $this->service->expects($this->once())
            ->method('findTagsQuantityByUserId')
            ->with($userId)
            ->willThrowException(new NotFoundServiceException("Теги для пользователя $userId не найдены"));

        $this->expectException(NotFoundServiceException::class);
        $this->expectExceptionMessage("Теги для пользователя $userId не найдены");

        $this->service->findTagsQuantityByUserId($userId);
    }

    public function testGetMockTagsQuantity()
    {

        $this->service->expects($this->once())
            ->method('getMockTagsQuantity')
            ->willReturn([]);

        $this->service->getMockTagsQuantity();
    }
}
