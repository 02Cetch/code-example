<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Setting;
use App\Service\Admin\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/settings', name: 'admin_settings')]
class SettingsController extends AbstractController
{
    public function __construct(private readonly SettingService $settingService)
    {
    }

    #[Route('/', name: '_index')]
    public function index(): Response
    {
        $settings = $this->settingService->getSettingsList()->getItems();
        return $this->render('/admin/pages/settings.html.twig', [
            'page_title' => "{$this->getParameter('app.name')} | Настройки",
            'settings' => $settings
        ]);
    }
}
