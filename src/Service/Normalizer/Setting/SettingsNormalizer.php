<?php

namespace App\Service\Normalizer\Setting;

use App\Entity\Admin\Setting;
use App\Exception\Normalizer\BadInputNormalizerException;

class SettingsNormalizer
{
    use SettingsNormalizerTrait;

    private array $typeToNormalizer;

    /**
     * @param Setting $setting
     * @return Setting
     * @throws BadInputNormalizerException
     */
    public function normalize(Setting $setting): Setting
    {
        $this->typeToNormalizer = $this->getSettingTypeToNormalizer();

        $settingTypeName = $setting->getType()->getName();
        if ($this->isValidatorExists($settingTypeName)) {
            $normalizer = $this->getNormalizerBySettingType($settingTypeName);
            $normalizedValue = $normalizer($setting->getDenormalizedValue());

            $setting->setValue($normalizedValue);
        }

        return $setting;
    }

    private function isValidatorExists(?string $type): bool
    {
        return isset($this->typeToNormalizer[$type]);
    }

    private function getNormalizerBySettingType(string $type): \Closure
    {
        return $this->typeToNormalizer[$type];
    }

    /**
     * @throws BadInputNormalizerException
     */
    private function getSettingTypeToNormalizer(): array
    {
        return [
            'url' => function ($value) {
                return $this->normalizeUrl($value);
            },
            'string' => function ($value) {
                return $this->normalizeString($value);
            }
        ];
    }
}
