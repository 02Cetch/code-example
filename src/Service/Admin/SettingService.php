<?php

namespace App\Service\Admin;

use App\Entity\Admin\Setting;
use App\Exception\Service\BadInputServiceException;
use App\Exception\Service\NotFoundServiceException;
use App\Helper\Settings\SettingsHelper;
use App\Repository\Admin\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;

class SettingService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SettingRepository $setting,
        private readonly SettingsHelper $helper
    ) {
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

    /**
     * @throws BadInputServiceException
     * @throws NotFoundServiceException
     */
    public function update(array $data): void
    {
        foreach ($data as $item) {
            if (!isset($item['id'], $item['value'])) {
                throw new BadInputServiceException('Missing required fields');
            }
            $setting = $this->getSettingById($item['id']);
            $setting->setValue($item['value']);
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