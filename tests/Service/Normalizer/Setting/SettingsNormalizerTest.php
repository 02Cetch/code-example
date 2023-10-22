<?php

namespace App\Tests\Service\Normalizer\Setting;

use App\Entity\Admin\Setting;
use App\Enum\SettingTypeName;
use App\Exception\Normalizer\BadInputNormalizerException;
use App\Service\Normalizer\Setting\SettingsNormalizer;
use App\Tests\AbstractTestCase;
use App\Tests\Factory\SettingFactory;
use App\Tests\Factory\SettingTypeFactory;
use Faker\Factory;
use Faker\Generator;

class SettingsNormalizerTest extends AbstractTestCase
{
    private readonly SettingsNormalizer $normalizer;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->normalizer = new SettingsNormalizer();
        $this->faker = Factory::create();

        parent::setUp();
    }

    public function testNormalizerBadInput()
    {
        $setting = $this->getSettingFromTypeName(SettingTypeName::URL, '//:invalidurl.');

        $this->expectException(BadInputNormalizerException::class);
        $this->expectExceptionMessage('URL передано в неправильном формате');

        $this->normalizer->normalize($setting);
    }

    private function getSettingFromTypeName(SettingTypeName $typeName, mixed $denormalizedValue = ''): Setting
    {
        $settingType = (new SettingTypeFactory())->create($typeName);

        return (new SettingFactory())->create(
            explode(' ', $this->faker->name())[0],
            $this->faker->title(),
            $denormalizedValue,
            type: $settingType
        );
    }
}
