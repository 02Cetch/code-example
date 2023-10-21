<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    public function mockEntity(string $classFqn, int $id): object
    {
        $entity = $this->createMock($classFqn);
        $entity->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        return $entity;
    }
}
