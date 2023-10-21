<?php

namespace App\Tests\Mapper;

use App\Dto\Response\Tag\TagsListItem;
use App\Exception\Mapper\BadInputMapperException;
use App\Mapper\TagsMapper;
use App\Tests\AbstractTestCase;
use Faker\Factory;
use Faker\Generator;

class TagsMapperTest extends AbstractTestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create('ru_RU');
    }

    public function testMap()
    {
        $tagData = [
            'title' => $this->faker->title(),
            'quantity' => $this->faker->randomNumber(),
        ];

        $expected = (new TagsListItem())
            ->setTitle($tagData['title'])
            ->setQuantity($tagData['quantity']);

        $dto = new TagsListItem();
        TagsMapper::map($tagData, $dto);

        $this->assertEquals($expected, $dto);
    }

    public function testMapBadInputMissingTitleIndex()
    {
        $tagData = [
            'title' => $this->faker->title()
        ];
        $dto = new TagsListItem();

        $this->expectException(BadInputMapperException::class);
        $this->expectExceptionMessage("Wrong tag data result");

        TagsMapper::map($tagData, $dto);
    }

    public function testMapBadInputMissingQuantityIndex()
    {
        $tagData = [
            'quantity' => $this->faker->randomNumber()
        ];
        $dto = new TagsListItem();

        $this->expectException(BadInputMapperException::class);
        $this->expectExceptionMessage("Wrong tag data result");

        TagsMapper::map($tagData, $dto);
    }
}
