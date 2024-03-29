<?php

namespace App\Service\Admin;

use App\Dto\Request\Setting\SettingsUpdateRequest;
use App\Dto\Response\Setting\SettingsListItem;
use App\Dto\Response\Setting\SettingsListResponse;
use App\Entity\Admin\Setting;
use App\Exception\Normalizer\BadInputNormalizerException;
use App\Exception\Service\NotFoundServiceException;
use App\Helper\Setting\SettingsHelper;
use App\Mapper\SettingMapper;
use App\Repository\Admin\SettingRepository;
use App\Service\Normalizer\Setting\SettingsNormalizer;
use Doctrine\ORM\EntityManagerInterface;

class SettingService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SettingRepository $setting,
        private readonly SettingsHelper $helper,
        private readonly SettingsNormalizer $normalizer
    ) {
    }

    public function getSettingsList(): SettingsListResponse
    {
        $settings = $this->setting->findAll();
        foreach ($settings as &$setting) {
            $setting = $this->helper->setFieldHtml($setting);
        }
        unset($setting);

        return new SettingsListResponse(array_map(function (Setting $setting) {
            $dto = new SettingsListItem();
            SettingMapper::map($setting, $dto);
            return $dto;
        }, $settings));
    }

    /**
     * @throws NotFoundServiceException
     * @throws BadInputNormalizerException
     */
    public function update(SettingsUpdateRequest $request): void
    {
        $items = $request->getItems();

        foreach ($items as $item) {
            $setting = $this->getSettingById($item->getId());
            $setting->setDenormalizedValue($item->getValue());

            $setting = $this->normalizer->normalize($setting);
            $this->entityManager->persist($setting);
        }
        $this->entityManager->flush();
    }

    /**
     * @throws NotFoundServiceException
     */
    public function getSettingById(int $id): Setting
    {
        $setting = $this->setting->find($id);
        if (!$setting) {
            throw new NotFoundServiceException("Setting with this id: $id was not found");
        }
        return $setting;
    }
}
