<?php

namespace App\Tests\Factory;

use App\Entity\Admin\SettingType;
use App\Enum\SettingTypeName;

class SettingTypeFactory
{
    public function create(SettingTypeName $name): SettingType
    {
        return (new SettingType())->setName($name);
    }
}
