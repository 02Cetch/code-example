<?php

namespace App\Tests\Mapper;

use App\Dto\Response\Tag\TagsListItem;
use App\Exception\Mapper\BadInputMapperException;
use App\Mapper\TagMapper;
use App\Tests\AbstractTestCase;
use Faker\Factory;
use Faker\Generator;

class TagMapperTest extends AbstractTestCase
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
            'quantity' => $this->faker->randomNumber('4'),
        ];

        $expected = (new TagsListItem())
            ->setTitle($tagData['title'])
            ->setQuantity($tagData['quantity']);

        $dto = new TagsListItem();
        TagMapper::map($tagData, $dto);

        $this->assertEquals($expected, $dto);
    }

    public function testMapBadInputMissingTitleIndex()
    {
        $tagData = [
            'quantity' => $this->faker->randomNumber(),
        ];
        $dto = new TagsListItem();

        $this->expectException(BadInputMapperException::class);
        $this->expectExceptionMessage("Wrong tag data result");

        TagMapper::map($tagData, $dto);
    }

    public function testMapBadInputMissingQuantityIndex()
    {
        $tagData = [
            'title' => $this->faker->title(),
        ];
        $dto = new TagsListItem();

        $this->expectException(BadInputMapperException::class);
        $this->expectExceptionMessage("Wrong tag data result");

        TagMapper::map($tagData, $dto);
    }
}
