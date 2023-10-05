<?php

namespace App\Tests\Service;

use App\Service\UserService;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $service;
    private \Faker\Generator $faker;

    protected function setUp(): void
    {
        $this->service = $this->createMock(UserService::class);
        $this->faker = Factory::create('ru_RU');

        parent::setUp();
    }

    public function testGetUserByIdNotFound()
    {
        $userId = $this->faker->numberBetween(1, 100000);

        $this->service->expects($this->once())
            ->method('getUserById')
            ->with($userId);

        $this->service->getUserById($userId);
    }
}
