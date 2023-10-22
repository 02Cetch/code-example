<?php

namespace App\Service\Normalizer\Setting;

use App\Exception\Normalizer\BadInputNormalizerException;

trait SettingsNormalizerTrait
{
    /**
     * @throws BadInputNormalizerException
     */
    public function normalizeUrl(string $url): array
    {
        $this->validateUrl($url);
        $data = [
            'value' => $url,
        ];
        return $this->normalizeField($data, $url);
    }

    public function normalizeString(string $str): array
    {
        return $this->normalizeField(['value' => $str], $str);
    }

    private function normalizeField(array $value, mixed $fieldValue): array
    {
        return array_merge($value, ['field_value' => $fieldValue]);
    }

    /**
     * @throws BadInputNormalizerException
     */
    private function validateUrl(mixed $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new BadInputNormalizerException("URL передано в неправильном формате");
        }
    }
}
