<?php

namespace App\Factory\Admin;

use App\Entity\Admin\Setting;
use App\Entity\Admin\SettingType;

class SettingFactory
{
    public function create(string $name, string $title, array $value, SettingType $type, $allowedValues = []): Setting
    {
        $setting = new Setting();

        $setting->setName($name);
        $setting->setTitle($title);
        $setting->setValue($value);
        $setting->setType($type);
        $setting->setAllowedValues($allowedValues);

        return $setting;
    }
}
