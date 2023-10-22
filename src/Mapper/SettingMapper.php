<?php

namespace App\Mapper;

use App\Dto\Response\Setting\BaseSettingDetails;
use App\Entity\Admin\Setting;

class SettingMapper
{
    public static function map(Setting $setting, BaseSettingDetails $dto): void
    {
        $dto
            ->setId($setting->getId())
            ->setName($setting->getName())
            ->setTitle($setting->getTitle())
            ->setValue($setting->getValue())
            ->setAllowedValues($setting->getAllowedValues())
            ->setFieldHtml($setting->getFieldHtml());
    }
}
