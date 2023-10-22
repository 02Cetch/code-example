<?php

namespace App\Tests\Mapper;

use App\Dto\Response\Setting\SettingsListItem;
use App\Entity\Admin\Setting;
use App\Mapper\SettingMapper;
use App\Tests\AbstractTestCase;
use Faker\Factory;
use Faker\Generator;

class SettingMapperTest extends AbstractTestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create('ru_RU');
    }

    public function testMap(): void
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->mockEntity(Setting::class, $this->faker->randomNumber());

        $expected = (new SettingsListItem())
            ->setId($setting->getId())
            ->setName($setting->getName())
            ->setTitle($setting->getTitle())
            ->setValue($setting->getValue())
            ->setAllowedValues($setting->getAllowedValues())
            ->setFieldHtml($setting->getFieldHtml());

        $dto = new SettingsListItem();
        SettingMapper::map($setting, $dto);

        $this->assertEquals($expected, $dto);
    }
}
