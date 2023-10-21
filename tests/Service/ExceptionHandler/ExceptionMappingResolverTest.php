<?php

namespace App\Tests\Service\ExceptionHandler;

use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractTestCase;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionMappingResolverTest extends AbstractTestCase
{
    /**
     * expects exception for missing http code
     */
    public function testMappingResolverInvalidArgument(): void
    {
        $class = "FooClass";
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("code is mandatory for class $class");

        new ExceptionMappingResolver([$class => ['hidden' => false, 'loggable' => true]]);
    }

    public function testResolverEqualsExpected(): void
    {
        $expectedMapping = new ExceptionMapping(
            $code = Response::HTTP_INTERNAL_SERVER_ERROR,
            $hidden = false,
            $loggable = false
        );

        $resolver = new ExceptionMappingResolver(
            [
                RuntimeException::class => [
                    'code' => $code,
                    'hidden' => $hidden,
                    'loggable' => $loggable
                ]
            ]
        );

        $mapping = $resolver->resolve(RuntimeException::class);
        $this->assertEquals($expectedMapping, $mapping, message: 'Mapping is not equal');
    }

    public function testResolvesToNullWhenNotFound(): void
    {
        $resolver = new ExceptionMappingResolver([]);
        $this->assertNull($resolver->resolve(\InvalidArgumentException::class));
    }

    public function testResolvesClassItself(): void
    {
        $resolver = new ExceptionMappingResolver([\InvalidArgumentException::class => ['code' => 400]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertEquals(400, $mapping->getCode());
        $this->assertTrue($mapping->isHidden());
        $this->assertFalse($mapping->isLoggable());
    }

    public function testResolvesSubClass(): void
    {
        $resolver = new ExceptionMappingResolver(
            [\LogicException::class => ['code' => Response::HTTP_INTERNAL_SERVER_ERROR]]
        );
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $mapping->getCode());
    }

    public function testResolvesHidden(): void
    {
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 500, 'hidden' => false]]);
        $mapping = $resolver->resolve(\LogicException::class);

        $this->assertFalse($mapping->isHidden());
    }

    public function testResolvesLoggable(): void
    {
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 500, 'loggable' => true]]);
        $mapping = $resolver->resolve(\LogicException::class);

        $this->assertTrue($mapping->isLoggable());
    }
}
