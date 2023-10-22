<?php

namespace App\Helper\Setting;

use App\Entity\Admin\Setting;
use App\Enum\SettingTypeName;

class SettingsHelper
{
    /**
     * sets field html
     */
    public function setFieldHtml(Setting $setting): Setting
    {
        $fieldValue = '';
        if ($setting->getValue()) {
            $fieldValue = $setting->getValue()['field_value'];
        }

        switch ($setting->getType()->getName()) {
            case SettingTypeName::BOOL->value:
                $setting->setFieldHtml(
                    "<input type=\"checkbox\" class=\"custom-control-input\" id=\"{$setting->getId()}\"
                                name=\"{$setting->getName()}\" value=\"$fieldValue\">
                             <label class=\"custom-control-label\" for=\"{$setting->getId()}\"></label>"
                );
                break;
            case SettingTypeName::URL->value:
                $setting->setFieldHtml(
                    "<input type=\"url\"
                                class=\"form-control\"
                                id=\"{$setting->getId()}\"
                                name=\"{$setting->getName()}\"
                                value=\"$fieldValue\" >"
                );
                break;
            default:
                $setting->setFieldHtml(
                    "<textarea
                                    class=\"form-control\" name=\"{$setting->getName()}\"
                                    rows=\"5\"
                                    placeholder=\"{$setting->getTitle()}\"
                                    id=\"{$setting->getId()}\"
                                    style=\"height: 53px;\">$fieldValue</textarea>"
                );
                break;
        }

        return $setting;
    }
}
