<?php

namespace App\Controller\Api;

use App\Dto\Request\Setting\SettingsUpdateRequest;
use App\Exception\Normalizer\BadInputNormalizerException;
use App\Exception\Service\NotFoundServiceException;
use App\Service\Admin\SettingService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/api/settings', name: 'api_settings')]
class SettingsController extends AbstractApiController
{
    public function __construct(private readonly SettingService $service)
    {
    }

    #[Route('/', name: '_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $settings = $this->service->getSettingsList()->getItems();
        return $this->respond('Settings list', $settings);
    }

    /**
     * @throws NotFoundServiceException
     * @throws BadInputNormalizerException
     */
    #[Route('/', name: '_update', methods: ['PUT', 'PATCH'])]
    public function update(
        #[MapRequestPayload] SettingsUpdateRequest $request
    ): JsonResponse {
        $this->service->update($request);
        return $this->respond('Settings successfully updated');
    }
}
