<?php

namespace App\Tests\Factory;

use App\Entity\Admin\Setting;
use App\Entity\Admin\SettingType;

class SettingFactory
{
    public function create(
        string $name,
        string $title,
        mixed $denormalizedValue,
        array $allowed_values = [],
        SettingType $type = null
    ): Setting {
        $setting = new Setting();
        $setting
            ->setName($name)
            ->setTitle($title)
            ->setAllowedValues($allowed_values)
            ->setType($type)
            ->setDenormalizedValue($denormalizedValue);
        return $setting;
    }
}
