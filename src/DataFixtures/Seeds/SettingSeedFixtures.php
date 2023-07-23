<?php

namespace App\DataFixtures\Seeds;

use App\Enum\SettingTypeName;
use App\Factory\Admin\SettingFactory;
use App\Factory\Admin\SettingTypeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SettingSeedFixtures extends Fixture implements FixtureGroupInterface
{
    private const SETTINGS = [
        [
            'name' => 'robots',
            'title' => 'Изменить robots.txt',
            'value' => [],
            'type' => SettingTypeName::STRING
        ],
        [
            'name' => 'telegram_link',
            'title' => 'Ссылка на телеграм канал',
            'value' => [],
            'type' => SettingTypeName::URL
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::SETTINGS as $setting) {
            $type = (new SettingTypeFactory())->create($setting['type']);
            $manager->persist($type);
            $setting = (new SettingFactory())->create($setting['name'], $setting['title'], $setting['value'], $type);
            $manager->persist($setting);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['seeds'];
    }
}
