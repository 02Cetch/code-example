<?php

namespace App\Service\Admin;

use App\Helper\Settings\SettingsHelper;
use App\Repository\Admin\SettingRepository;

class SettingService
{
    public function __construct(private readonly SettingRepository $setting, private readonly SettingsHelper $helper)
    {
    }

    public function getSettings(): array
    {
        $settings = $this->setting->findAll();
        foreach ($settings as &$setting) {
            $setting = $this->helper->setFieldHtml($setting);
        }
        unset($setting);

        return $settings;
    }
}
